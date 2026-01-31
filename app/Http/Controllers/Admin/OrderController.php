<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Order;
use App\Services\VTU\EpinsVTUService;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('reference', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($q) use ($request) {
                      $q->where('email', 'like', '%' . $request->search . '%');
                  });
        }

        $orders = $query->paginate(20)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        return view('admin.orders.show', compact('order'));
    }

    public function retry(Order $order)
    {
        if ($order->status === 'success') {
            return back()->with('error', 'Transaction is already successful.');
        }

        try {
            // Simplified retry logic - currently supporting EPINS
            if ($order->type === 'vtu-data' || $order->type === 'vtu-airtime') {
                $service = new EpinsVTUService();
                $details = $order->details ?? [];
                
                // Re-construct payload from details if possible, or Order model needs to store original payload better.
                // Assuming 'details' contains enough info (phone, plan_code/amount, network)
                
                $payload = [
                    'network' => $details['network'] ?? '',
                    'phone' => $details['phone'] ?? '',
                    'reference' => $order->reference . '_retry_' . time(), // Unique ref for retry
                ];

                if ($order->type === 'vtu-data') {
                    $payload['plan_code'] = $details['plan_code'] ?? '';
                    $response = $service->purchaseData($payload);
                } else {
                    $payload['amount'] = $order->amount;
                    $response = $service->purchaseAirtime($payload);
                }

                // Update logs
                $logs = $order->api_response ?? [];
                $logs[] = [
                    'retry_at' => now()->toIso8601String(),
                    'response' => $response->data
                ];
                
                if ($response->success) {
                    $order->update([
                        'status' => 'success',
                        'api_response' => $logs
                    ]);
                    return back()->with('success', 'Retry successful.');
                } else {
                    $order->update(['api_response' => $logs]);
                    return back()->with('error', 'Retry failed: ' . $response->message);
                }
            }

            return back()->with('error', 'Retry not supported for this transaction type.');

        } catch (\Exception $e) {
            Log::error('Order Retry Error: ' . $e->getMessage());
            return back()->with('error', 'Retry exception: ' . $e->getMessage());
        }
    }
}
