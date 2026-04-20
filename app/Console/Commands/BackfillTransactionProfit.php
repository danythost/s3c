<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\WalletTransaction;
use App\Models\DataPlan;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class BackfillTransactionProfit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:backfill-profit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backfill missing profit and cost_price for successful transactions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $affected = 0;
        $fixed_airtime = 0;
        $fixed_data = 0;

        $this->info("Starting backfill of missing profit calculations...");

        $query = WalletTransaction::whereIn('status', ['success', 'completed'])
            ->where(function($q) {
                $q->whereNull('profit')->orWhere('profit', 0);
            });

        $total = $query->count();
        $this->info("Found {$total} potential transactions to fix.");

        $query->chunk(100, function($transactions) use (&$affected, &$fixed_airtime, &$fixed_data) {
            foreach ($transactions as $txn) {
                $amount = $txn->amount;
                $meta = $txn->meta ?? [];
                $newProfit = null;
                $newCost = null;

                if ($txn->source === 'airtime') {
                    $network = strtolower($meta['network'] ?? '');
                    if ($network) {
                        $settingKey = 'peyflex_airtime_' . $network;
                        $rate = (float) Setting::getValue($settingKey, config('vtu.peyflex.airtime_rates.' . $network, 0));
                        $newProfit = $amount * ($rate / 100);
                        $newCost = $amount - $newProfit;
                        $fixed_airtime++;
                    }
                } elseif ($txn->source === 'data') {
                    $planId = $meta['plan_id'] ?? null;
                    $plan = null;
                    if ($planId) {
                        $plan = DataPlan::find($planId);
                    }
                    
                    if (!$plan) {
                        $planCode = $meta['plan_code'] ?? null;
                        if ($planCode) {
                            $plan = DataPlan::where('code', $planCode)->first();
                        }
                    }

                    if ($plan) {
                        $newCost = $plan->provider_price;
                        $newProfit = $amount - $newCost;
                        $fixed_data++;
                    }
                }

                if ($newProfit !== null) {
                    $txn->update([
                        'profit' => $newProfit,
                        'cost_price' => $newCost ?? $txn->cost_price
                    ]);
                    $affected++;
                }
            }
            $this->info("Processed " . ($affected) . " transactions so far...");
        });

        $this->info("\nBackfill completed!");
        $this->info("Total transactions fixed: $affected");
        $this->info("Airtime transactions fixed: $fixed_airtime");
        $this->info("Data transactions fixed: $fixed_data");
    }
}
