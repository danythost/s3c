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
        return view('vtu.data.index');
    }

    /**
     * Handle the data purchase request
     */
    public function purchase(Request $request, PurchaseData $purchaseData)
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'digits:11'],
            'network' => ['required', 'string'],
            'plan_id' => ['required', 'string'],
            'amount' => ['required', 'numeric', 'min:1'],
        ]);

        $response = $purchaseData->execute($request->user(), $validated);

        if ($response->success) {
            return back()->with('success', $response->message);
        }

        return back()->with('error', $response->message)->withInput();
    }
}
