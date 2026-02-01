<?php

namespace App\Actions\VTU;

use App\Contracts\VTU\VTUProviderInterface;
use App\Actions\Wallet\DebitWallet;
use App\Actions\Wallet\ReverseWallet;
use App\Models\Order;
use App\Models\WalletTransaction;
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
        // Generate a highly unique 17-character reference: D (1) + hex-time (8) + hex-rand (8)
        $reference = $data['reference'] ?? 'D' . dechex(time()) . bin2hex(random_bytes(4));
        $amount = $data['amount'];
        $userId = $user->id;

        // 1. Idempotency Check (Step 3 & 7)
        $existing = WalletTransaction::where('reference', $reference)->first();
        if ($existing) {
            if ($existing->status === 'pending') {
                // If it's still pending, we might want to resume the call or tell user it's processing
                // For this implementation, we'll try to resume if it was a timeout/manual retry
                return $this->processVTUCall($existing, $user, $data, $reference);
            }
            return VTUResponse::success('Transaction already processed: ' . $existing->status, $existing->meta, $reference);
        }

        try {
            // 2. Initiate Transaction: Debit + Create Pending Record (Phase 1)
            $txn = DB::transaction(function () use ($userId, $amount, $reference, $data) {
                // Lock and Debit
                $this->debitWallet->execute($userId, $amount);

                // Create Transaction record as Shield
                return WalletTransaction::create([
                    'user_id'   => $userId,
                    'reference' => $reference,
                    'amount'    => $amount,
                    'type'      => 'debit',
                    'status'    => 'pending',
                    'source'    => 'data',
                    'meta'      => [
                        'network' => $data['network'],
                        'phone'   => $data['phone'],
                        'plan_id' => $data['plan_id'] ?? null,
                    ],
                ]);
            });

            // 3. Process VTU Call (Phase 2)
            return $this->processVTUCall($txn, $user, $data, $reference);

        } catch (\Illuminate\Database\QueryException $e) {
            // Database-level idempotency shield (Step 5)
            return VTUResponse::failure('Duplicate transaction prevented', ['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            return VTUResponse::failure('System error: ' . $e->getMessage());
        }
    }

    /**
     * Call the VTU provider and finalize the transaction
     */
    protected function processVTUCall(WalletTransaction $txn, User $user, array $data, string $reference): VTUResponse
    {
        $amount = $txn->amount;
        $userId = $user->id;

        try {
            $payload = array_merge($data, ['reference' => $reference]);
            $response = $this->vtuProvider->purchaseData($payload);

            return DB::transaction(function () use ($txn, $response, $userId, $amount) {
                if (!$response->success) {
                    // REFUND (Step 4 & 7)
                    $this->reverseWallet->execute($userId, $amount);

                    $txn->update([
                        'status' => 'failed',
                        'meta'   => array_merge($txn->meta ?? [], $response->data ?? []),
                    ]);

                    return $response;
                }

                // SUCCESS
                $txn->update([
                    'status' => 'success',
                    'meta'   => array_merge($txn->meta ?? [], $response->data ?? []),
                ]);

                return $response;
            });

        } catch (\Exception $e) {
            // On unexpected exception (like provider timeout), we leave it as PENDING
            // The user can retry later, and Step 1 will catch it.
            return VTUResponse::failure('VTU call failed: ' . $e->getMessage() . '. You can retry safely.');
        }
    }
}
