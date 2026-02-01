<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Actions\VTU\PurchaseAirtime;
use Illuminate\Http\Request;

class AirtimeController extends Controller
{
    /**
     * Display the airtime purchase page
     */
    public function index()
    {
        return view('vtu.airtime.index');
    }

    /**
     * Handle the airtime purchase request
     */
    public function purchase(Request $request, PurchaseAirtime $purchaseAirtime)
    {
        $validated = $request->validate([
            'phone'   => ['required', 'string', 'digits:11'],
            'network' => ['required', 'string', 'in:MTN,Airtel,GLO,9mobile,AIRTEL,9MOBILE'],
            'amount'  => ['required', 'numeric', 'min:100', 'max:50000'],
        ]);

        $response = $purchaseAirtime->execute($request->user(), $validated);

        if ($response->success) {
            return back()->with('success', $response->message);
        }

        return back()->with('error', $response->message)->withInput();
    }
}
