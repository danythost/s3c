<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\VirtualAccount;
use App\Services\Flutterwave\FlutterwaveService;
use Illuminate\Console\Command;

class GenerateVirtualAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flw:generate-va {user_id?} {--bvn=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Flutterwave Virtual Accounts for users';

    /**
     * Execute the console command.
     */
    public function handle(FlutterwaveService $flwService)
    {
        $userId = $this->argument('user_id');

        $users = $userId 
            ? User::where('id', $userId)->get() 
            : User::whereDoesntHave('virtualAccount')->get();

        if ($users->isEmpty()) {
            $this->info('No users found needing virtual accounts.');
            return;
        }

        $this->info("Found {$users->count()} users. Generating accounts...");

        foreach ($users as $user) {
            $this->info("Generating for: {$user->email}");

            $result = $flwService->createVirtualAccount([
                'user_id'   => $user->id,
                'email'     => $user->email,
                'firstname' => explode(' ', $user->name)[0] ?? 'User',
                'lastname'  => explode(' ', $user->name)[1] ?? $user->id,
                'bvn'       => $this->option('bvn') ?? config('services.flutterwave.test_bvn'),
            ]);

            if ($result['success']) {
                VirtualAccount::create([
                    'user_id'           => $user->id,
                    'account_number'    => $result['account_number'],
                    'bank_name'         => $result['bank_name'],
                    'account_reference' => $result['account_reference'],
                    'provider'          => 'flutterwave',
                ]);
                $this->info("Successfully created account: {$result['account_number']}");
            } else {
                $this->error("Failed for {$user->email}: " . ($result['message'] ?? 'Unknown error'));
            }
        }

        $this->info('Done!');
    }
}
