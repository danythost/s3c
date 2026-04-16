<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Log;
use App\Contracts\VTU\VTUProviderInterface;

class FinanceController extends Controller
{
    public function index()
    {
        $stats = [
            'total_user_balance' => Wallet::sum('balance'),
            'total_transactions' => WalletTransaction::count(),
            'total_funded' => WalletTransaction::where('type', 'credit')->where('status', 'success')->sum('amount'), // Approximate
            'total_withdrawals' => WalletTransaction::where('type', 'debit')->where('status', 'success')->sum('amount'), // Approximate
            'total_profit' => WalletTransaction::where('status', 'success')->sum('profit'),
            'total_commissions' => WalletTransaction::where('type', 'commission')->where('status', 'success')->sum('amount'),
        ];

        return view('admin.finance.index', compact('stats'));
    }

    public function transactions(Request $request)
    {
        $query = WalletTransaction::with('user')->latest();

        if ($request->filled('search')) {
            $query->where('reference', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($q) use ($request) {
                      $q->where('email', 'like', '%' . $request->search . '%');
                  });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $transactions = $query->paginate(20)->withQueryString();
        
        return view('admin.finance.transactions', compact('transactions'));
    }

    public function fund()
    {
        return view('admin.finance.fund');
    }

    public function storeFund(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'amount' => 'required|numeric|min:1',
            'type' => 'required|in:credit,debit',
            'description' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $user = User::where('email', $request->email)->firstOrFail();
            $wallet = $user->wallet()->firstOrCreate([]); // Ensure wallet exists

            $reference = 'ADMIN_' . strtoupper(uniqid());
            $amount = $request->amount;

            if ($request->type === 'credit') {
                $wallet->increment('balance', $amount);
                $initialStatus = 'success';
            } else {
                if ($wallet->balance < $amount) {
                    throw new \Exception("Insufficient wallet balance for debit.");
                }
                $wallet->decrement('balance', $amount);
                $initialStatus = 'success';
            }

            WalletTransaction::create([
                'user_id' => $user->id,
                'reference' => $reference,
                'type' => $request->type === 'credit' ? 'admin_credit' : 'admin_debit',
                'amount' => $amount,
                'status' => $initialStatus,
                'source' => 'admin',
                'meta' => ['description' => $request->description, 'admin_action' => true],
            ]);

            DB::commit();

            return back()->with('success', "Wallet successfully {$request->type}ed.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Manual Funding Error: ' . $e->getMessage());
            return back()->with('error', 'Funding failed: ' . $e->getMessage());
        }
    }

    public function provider(VTUProviderInterface $vtuService)
    {
        // Provider balance check
        $providerBalance = Cache::remember('provider_balance', 300, function () use ($vtuService) {
            try {
                $response = $vtuService->getBalance();
                return $response->success ? ($response->data['wallet_credit'] ?? ($response->data['balance'] ?? 0)) : null;
            } catch (\Exception $e) {
                return null;
            }
        });

        return view('admin.finance.provider', compact('providerBalance'));
    }
}
