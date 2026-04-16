<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Actions\VTU\PurchaseAirtime;
use Illuminate\Http\Request;

class AirtimeController extends Controller
{
    /**
     * Handle the airtime purchase request via API.
     */
    public function purchase(Request $request, PurchaseAirtime $purchaseAirtime)
    {
        $validated = $request->validate([
            'phone'   => ['required', 'string', 'digits:11'],
            'network' => ['required', 'string', 'in:MTN,Airtel,GLO,9mobile,AIRTEL,9MOBILE'],
            'amount'  => ['required', 'numeric', 'min:100', 'max:50000'],
        ]);

        try {
            $response = $purchaseAirtime->execute($request->user(), $validated);
            
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
