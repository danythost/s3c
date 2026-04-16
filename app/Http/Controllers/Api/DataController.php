<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DataPlan;
use App\Actions\VTU\PurchaseData;
use Illuminate\Http\Request;

class DataController extends Controller
{
    /**
     * Get all active data plans grouped by network.
     */
    public function index()
    {
        $plans = DataPlan::where('is_active', true)
            ->where('provider', 'epins')
            ->get();

        return response()->json([
            'plans' => $plans
        ]);
    }

    /**
     * Handle the data purchase request via API.
     */
    public function purchase(Request $request, PurchaseData $purchaseData)
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'digits:11'],
            'network' => ['required', 'string'],
            'plan_id' => ['required', 'exists:data_plans,id'],
        ]);

        $plan = DataPlan::findOrFail($validated['plan_id']);

        $payload = [
            'phone'     => $validated['phone'],
            'network'   => $validated['network'],
            'plan_code' => $plan->code,
            'plan_id'   => $plan->id,
            'amount'    => $plan->selling_price,
        ];

        try {
            $response = $purchaseData->execute($request->user(), $payload);
            
            return response()->json([
                'success' => $response->success,
                'message' => $response->message,
            ], $response->success ? 200 : 400);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'System error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }
}
