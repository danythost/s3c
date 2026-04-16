<?php

namespace App\Listeners;

use App\Models\VirtualAccount;
use App\Services\Flutterwave\FlutterwaveService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Log;

class CreateVirtualAccountForUser
{
    public function __construct(protected FlutterwaveService $flwService)
    {
    }

    /**
     * Handle the event.
     * Failures here must NEVER break the registration response.
     */
    public function handle(Registered $event): void
    {
        $user = $event->user;

        try {
            // Use a direct DB check — Eloquent relation cache can be stale
            $alreadyExists = VirtualAccount::where('user_id', $user->id)->exists();
            if ($alreadyExists) {
                Log::info("VA already exists for user {$user->id}, skipping.");
                return;
            }

            $result = $this->flwService->createVirtualAccount([
                'user_id'     => $user->id,
                'email'       => $user->email,
                'va_username' => 's3c-' . ($user->username ?? $user->id),
                'phone'       => $user->phone ?? null,
                'bvn'         => config('services.flutterwave.test_bvn'),
            ]);

            if (!$result['success']) {
                Log::error("VA creation failed for user {$user->id}: " . ($result['message'] ?? 'Unknown error'));
                return;
            }

            try {
                VirtualAccount::create([
                    'user_id'           => $user->id,
                    'account_number'    => $result['account_number'],
                    'bank_name'         => $result['bank_name'],
                    'account_reference' => $result['account_reference'],
                    'provider'          => 'flutterwave',
                ]);
                Log::info("VA created successfully for user {$user->id}: {$result['account_number']}");
            } catch (UniqueConstraintViolationException $e) {
                // Race condition — another process already inserted it, safe to ignore
                Log::warning("VA duplicate insert ignored for user {$user->id} (already created).");
            }

        } catch (\Throwable $e) {
            // Log but never throw — registration must succeed regardless of VA status
            Log::error("VA listener exception for user {$user->id}: " . $e->getMessage());
        }
    }
}
