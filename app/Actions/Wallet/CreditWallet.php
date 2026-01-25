<?php

namespace App\Actions\Wallet;

use App\Repositories\WalletRepository;
use Illuminate\Support\Facades\DB;

class CreditWallet
{
    public function __construct(
        protected WalletRepository $walletRepository
    ) {}

    /**
     * Credit the user's wallet within a transaction
     */
    public function execute(int $userId, float $amount): void
    {
        DB::transaction(function () use ($userId, $amount) {
            $wallet = $this->walletRepository->lockForUser($userId);
            $this->walletRepository->credit($wallet, $amount);
        });
    }
}
