<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\WalletTransaction;
use App\Contracts\VTU\VTUProviderInterface;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = WalletTransaction::with('user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('reference', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($q) use ($request) {
                      $q->where('email', 'like', '%' . $request->search . '%')
                        ->orWhere('name', 'like', '%' . $request->search . '%');
                  });
        }

        $transactions = $query->paginate(20)->withQueryString();

        return view('admin.orders.index', compact('transactions'));
    }

    public function show($id)
    {
        $transaction = WalletTransaction::with('user')->findOrFail($id);
        return view('admin.orders.show', compact('transaction'));
    }

    public function retry($id, VTUProviderInterface $vtuService)
    {
        $transaction = WalletTransaction::findOrFail($id);

        if ($transaction->status === 'success') {
            return back()->with('error', 'Transaction is already successful.');
        }

        try {
            // Re-use VTU provider interface for retries
            if ($transaction->source === 'data' || $transaction->source === 'airtime') {
                $meta = $transaction->meta ?? [];
                
                $payload = [
                    'network' => $meta['network'] ?? '',
                    'phone' => $meta['phone'] ?? '',
                    'reference' => 'RETRY_' . strtoupper(uniqid()),
                ];

                if ($transaction->source === 'data') {
                    $planCode = $meta['plan_code'] ?? null;
                    if (!$planCode && !empty($meta['plan_id'])) {
                        $plan = \App\Models\DataPlan::find($meta['plan_id']);
                        $planCode = $plan ? $plan->code : null;
                    }

                    if (!$planCode) {
                        return back()->with('error', 'Cannot retry: Plan info missing in transaction record.');
                    }

                    $payload['plan_code'] = $planCode;
                    $payload['plan_id'] = $meta['plan_id'] ?? null;
                    $response = $vtuService->purchaseData($payload);
                } else {
                    $payload['amount'] = $transaction->amount;
                    $response = $vtuService->purchaseAirtime($payload);
                }

                // Update logs in transaction meta
                $logs = $transaction->meta['retry_logs'] ?? [];
                $logs[] = [
                    'retry_at' => now()->toIso8601String(),
                    'success' => $response->success,
                    'message' => $response->message,
                    'response' => $response->data
                ];
                
                $newMeta = array_merge($transaction->meta, ['retry_logs' => $logs]);

                if ($response->success) {
                    $transaction->update([
                        'status' => 'success',
                        'meta' => $newMeta
                    ]);
                    return back()->with('success', 'Retry successful.');
                } else {
                    $transaction->update(['meta' => $newMeta]);
                    return back()->with('error', 'Retry failed: ' . $response->message);
                }
            }

            return back()->with('error', 'Retry not supported for this transaction type.');

        } catch (\Exception $e) {
            Log::error('Transaction Retry Error: ' . $e->getMessage());
            return back()->with('error', 'Retry exception: ' . $e->getMessage());
        }
    }
}
