<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Contracts\VTU\VTUProviderInterface;
use App\Services\VTU\PeyflexVTUService;

class SyncPeyflexData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vtu:sync-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync data plans from Peyflex API';

    /**
     * Execute the console command.
     */
    public function handle(VTUProviderInterface $vtuService)
    {
        if (!$vtuService instanceof PeyflexVTUService) {
            $this->error("PeyflexVTUService is not the active VTU provider.");
            return 1;
        }

        $this->info("Starting Peyflex Data Plan Sync...");

        try {
            $result = $vtuService->syncDataPlans();
            
            $this->info("Successfully imported {$result['imported']} data plans.");
            
            if (!empty($result['errors'])) {
                $this->warn("The following errors occurred:");
                foreach ($result['errors'] as $error) {
                    $this->error($error);
                }
            }

            return 0;
        } catch (\Exception $e) {
            $this->error("Sync failed: " . $e->getMessage());
            return 1;
        }
    }
}
