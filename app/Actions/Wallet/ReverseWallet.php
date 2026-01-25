<?php

namespace App\Actions\Wallet;

use App\Repositories\WalletRepository;
use Illuminate\Support\Facades\DB;

class ReverseWallet
{
    public function __construct(
        protected WalletRepository $walletRepository
    ) {}

    /**
     * Reverse a transaction by crediting the wallet back
     */
    public function execute(int $userId, float $amount): void
    {
        DB::transaction(function () use ($userId, $amount) {
            $wallet = $this->walletRepository->lockForUser($userId);
            $this->walletRepository->credit($wallet, $amount);
        });
    }
}
