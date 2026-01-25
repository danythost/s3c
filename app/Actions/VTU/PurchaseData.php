<?php

namespace App\Actions\VTU;

use App\Contracts\VTU\VTUProviderInterface;
use App\Actions\Wallet\DebitWallet;
use App\Actions\Wallet\ReverseWallet;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\User;
use App\Domains\VTU\VTUResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class PurchaseData
{
    public function __construct(
        protected VTUProviderInterface $vtuProvider,
        protected DebitWallet $debitWallet,
        protected ReverseWallet $reverseWallet
    ) {}

    /**
     * Execute the data purchase action with full money flow
     */
    public function execute(User $user, array $data): VTUResponse
    {
        return DB::transaction(function () use ($user, $data) {
            $orderReference = Str::uuid()->toString();
            $amount = $data['amount'];
            $userId = $user->id;

            try {
                // 1. Debit wallet
                $this->debitWallet->execute($userId, $amount);

                // 2. Create order
                $order = Order::create([
                    'user_id'   => $userId,
                    'reference' => $orderReference,
                    'amount'    => $amount,
                    'type'      => 'vtu-data',
                    'status'    => 'pending',
                ]);

                // 3. Call VTU provider
                $payload = array_merge($data, ['reference' => $orderReference]);
                $response = $this->vtuProvider->purchaseData($payload);

                // 4. Handle failure
                if (!$response->success) {
                    // Reverse wallet
                    $this->reverseWallet->execute($userId, $amount);

                    $order->update(['status' => 'failed']);

                    Transaction::create([
                        'user_id'   => $userId,
                        'order_id'  => $order->id,
                        'reference' => $response->reference ?? $orderReference,
                        'type'      => 'reversal',
                        'amount'    => $amount,
                        'status'    => 'success',
                        'meta'      => $response->data,
                    ]);

                    return $response;
                }

                // 5. Success
                $order->update(['status' => 'success']);

                Transaction::create([
                    'user_id'   => $userId,
                    'order_id'  => $order->id,
                    'reference' => $response->reference ?? $orderReference,
                    'type'      => 'debit',
                    'amount'    => $amount,
                    'status'    => 'success',
                    'meta'      => $response->data,
                ]);

                return $response;

            } catch (\Exception $e) {
                // Unexpected error (e.g. database or internal logic)
                return VTUResponse::failure('System error: ' . $e->getMessage());
            }
        });
    }
}
