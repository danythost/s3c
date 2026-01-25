<?php

namespace App\Repositories;

use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class WalletRepository
{
    /**
     * Lock the wallet for update to prevent race conditions
     */
    public function lockForUser(int $userId): Wallet
    {
        return Wallet::where('user_id', $userId)
            ->lockForUpdate()
            ->firstOrFail();
    }

    /**
     * Credit the wallet balance
     */
    public function credit(Wallet $wallet, float $amount): Wallet
    {
        if ($amount <= 0) {
            throw new RuntimeException('Invalid credit amount');
        }

        $wallet->increment('balance', $amount);

        return $wallet->refresh();
    }

    /**
     * Debit the wallet balance
     */
    public function debit(Wallet $wallet, float $amount): Wallet
    {
        if ($amount <= 0) {
            throw new RuntimeException('Invalid debit amount');
        }

        if ($wallet->balance < $amount) {
            throw new RuntimeException('Insufficient wallet balance');
        }

        $wallet->decrement('balance', $amount);

        return $wallet->refresh();
    }
}
