<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\WalletTransaction;
use App\Services\VTU\EpinsVTUService;
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

    public function retry($id)
    {
        $transaction = WalletTransaction::findOrFail($id);

        if ($transaction->status === 'success') {
            return back()->with('error', 'Transaction is already successful.');
        }

        try {
            // Simplified retry logic - currently supporting EPINS
            if ($transaction->source === 'data' || $transaction->source === 'airtime') {
                $service = new EpinsVTUService();
                $meta = $transaction->meta ?? [];
                
                $payload = [
                    'network' => $meta['network'] ?? '',
                    'phone' => $meta['phone'] ?? '',
                    // Generate new 17-char ref: R + 8 hex time + 8 hex rand
                    'reference' => 'R' . dechex(time()) . bin2hex(random_bytes(4)),
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
                    $response = $service->purchaseData($payload);
                } else {
                    $payload['amount'] = $transaction->amount;
                    $response = $service->purchaseAirtime($payload);
                }

                // Update logs
                $logs = $transaction->meta['api_response'] ?? [];
                $logs[] = [
                    'retry_at' => now()->toIso8601String(),
                    'response' => $response->data
                ];
                
                $newMeta = array_merge($transaction->meta, ['api_response' => $logs]);

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
