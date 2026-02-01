<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Actions\VTU\PurchaseData;
use Illuminate\Http\Request;

class DataController extends Controller
{
    /**
     * Display the data purchase page
     */
    public function index()
    {
        $plans = \App\Models\DataPlan::where('is_active', true)
            ->where('provider', 'epins')
            ->get()
            ->groupBy(fn($plan) => strtoupper($plan->network));

        return view('vtu.data.index', compact('plans'));
    }

    /**
     * Handle the data purchase request
     */
    public function purchase(Request $request, PurchaseData $purchaseData)
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'digits:11'],
            'network' => ['required', 'string'],
            'plan_id' => ['required', 'exists:data_plans,id'],
        ]);

        $plan = \App\Models\DataPlan::findOrFail($validated['plan_id']);

        $payload = [
            'phone'     => $validated['phone'],
            'network'   => $validated['network'],
            'plan_code' => $plan->code,
            'amount'    => $plan->selling_price,
        ];

        $response = $purchaseData->execute($request->user(), $payload);

        if ($response->success) {
            return back()->with('success', $response->message);
        }

        return back()->with('error', $response->message)->withInput();
    }
}
