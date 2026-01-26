<?php

namespace App\Console\Commands;

use App\Services\VTU\EpinsVTUService;
use Illuminate\Console\Command;

class SyncEpinsPlans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vtu:sync-epins';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync data plans from EPINS to local database';

    /**
     * Execute the console command.
     */
    public function handle(\App\Actions\VTU\SyncEpinsDataPlans $syncAction)
    {
        $this->info('Starting EPINS data plan sync...');

        try {
            $syncAction->execute();
            $this->info('Successfully synced data plans');
        } catch (\Exception $e) {
            $this->error('Sync failed: ' . $e->getMessage());
        }
    }
}
