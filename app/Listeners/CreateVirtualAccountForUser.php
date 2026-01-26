<?php

namespace App\Listeners;

use App\Models\VirtualAccount;
use App\Services\Flutterwave\FlutterwaveService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class CreateVirtualAccountForUser implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(protected FlutterwaveService $flwService)
    {
    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        $user = $event->user;

        // Don't create if already exists
        if ($user->virtualAccount) {
            return;
        }

        $result = $this->flwService->createVirtualAccount([
            'user_id'   => $user->id,
            'email'     => $user->email,
            'firstname' => explode(' ', $user->name)[0] ?? 'User',
            'lastname'  => explode(' ', $user->name)[1] ?? $user->id,
            'phone'     => $user->phone ?? null,
            'bvn'       => config('services.flutterwave.test_bvn'),
        ]);

        if ($result['success']) {
            VirtualAccount::create([
                'user_id'           => $user->id,
                'account_number'    => $result['account_number'],
                'bank_name'         => $result['bank_name'],
                'account_reference' => $result['account_reference'],
                'provider'          => 'flutterwave',
            ]);
        } else {
            Log::error("Failed to create VA for user {$user->id}: " . ($result['message'] ?? 'Unknown error'));
        }
    }
}
