<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;

class ResetRevenue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'revenue:reset {--force : Force the operation without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset overall revenue by clearing orders and wallet transactions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('force') && !$this->confirm('This will PERMANENTLY delete all orders and wallet transactions. Do you want to continue?')) {
            $this->info('Operation cancelled.');
            return;
        }

        $this->info('Starting revenue reset...');

        try {
            DB::transaction(function () {
                Schema::disableForeignKeyConstraints();
                
                DB::table('orders')->truncate();
                DB::table('wallet_transactions')->truncate();
                
                Schema::enableForeignKeyConstraints();
            });

            // Clear statistics related cache
            Cache::forget('provider_balance');

            $this->info('Revenue reset successfully! All transaction data has been cleared.');
        } catch (\Exception $e) {
            $this->error('Reset failed: ' . $e->getMessage());
        }
    }
}
