<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\A2CRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class A2CController extends Controller
{
    public function index()
    {
        return view('a2c.index');
    }

    public function create()
    {
        $activeRequest = A2CRequest::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->first();

        if ($activeRequest) {
            return redirect()->route('a2c.instructions', $activeRequest->id)
                ->with('info', 'You have an active request. Please complete it first.');
        }

        return view('a2c.create');
    }

    public function store(Request $request)
    {
        $activeRequest = A2CRequest::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->first();

        if ($activeRequest) {
            return redirect()->route('a2c.instructions', $activeRequest->id)
                ->with('error', 'You already have an active request.');
        }

        $request->validate([
            'network' => 'required|string|in:MTN,AIRTEL,GLO,9MOBILE',
            'amount' => 'required|numeric|min:' . config('airtime2cash.min_amount'),
            'phone' => 'required|string',
        ]);

        // VTUAfrica Service Verification (MANDATORY FIRST STEP)
        try {
            $response = \Illuminate\Support\Facades\Http::timeout(40)
                ->asForm()
                ->post(config('services.vtuafrica.base_url') . '/merchant-verify/', [
                    'apikey'      => config('services.vtuafrica.key'),
                    'serviceName' => 'Airtime2Cash',
                    'network'     => strtolower($request->network),
                ]);

            if (!$response->successful() || $response->json('code') != 101) {
                return back()->with('error', 'Service currently unavailable from provider. ' . $response->json('message'));
            }

            $sitephone = $response->json('sitephone');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('VTUAfrica Verify Error: ' . $e->getMessage());
            return back()->with('error', 'Could not connect to service provider. Please try again later.');
        }

        $rate = config('airtime2cash.rates.' . $request->network);
        $payable = floor($request->amount * $rate);

        $a2cRequest = A2CRequest::create([
            'user_id' => auth()->id(),
            'network' => $request->network,
            'amount' => $request->amount,
            'payable' => $payable,
            'phone' => $request->phone,
            'sitephone' => $sitephone,
            'reference' => 'A2C-' . strtoupper(Str::random(10)),
            'status' => 'pending',
        ]);

        return redirect()->route('a2c.instructions', $a2cRequest->id);
    }

    public function confirm($id)
    {
        $a2cRequest = A2CRequest::where('user_id', auth()->id())->findOrFail($id);

        if ($a2cRequest->status !== 'pending') {
            return redirect()->route('a2c.status', $a2cRequest->id);
        }

        // VTUAfrica Airtime to Cash Conversion (AFTER TRANSFER)
        try {
            $response = \Illuminate\Support\Facades\Http::timeout(40)
                ->asForm()
                ->post(config('services.vtuafrica.base_url') . '/airtime-cash/', [
                    'apikey'       => config('services.vtuafrica.key'),
                    'network'      => strtolower($a2cRequest->network),
                    'sender'       => auth()->user()->email,
                    'sendernumber' => $a2cRequest->phone,
                    'amount'       => (int) $a2cRequest->amount,
                    'sitephone'    => $a2cRequest->sitephone,
                    'ref'          => $a2cRequest->reference,
                    'webhookURL'   => route('webhooks.vtuafrica'),
                ]);

            if (!$response->successful()) {
                return back()->with('error', 'Failed to submit conversion to provider. Please try again.');
            }

            // We don't change status to completed here. We wait for the webhook.
            // But we can mark it as 'submitted' if we had a status for it, 
            // for now we just redirect to status page.
            return redirect()->route('a2c.status', $a2cRequest->id);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('VTUAfrica Confirm Error: ' . $e->getMessage());
            return back()->with('error', 'Could not submit conversion request. ' . $e->getMessage());
        }
    }

    public function instructions($id)
    {
        $a2cRequest = A2CRequest::where('user_id', auth()->id())->findOrFail($id);
        
        if ($a2cRequest->status !== 'pending') {
            return redirect()->route('a2c.status', $a2cRequest->id);
        }

        return view('a2c.instructions', ['request' => $a2cRequest]);
    }

    public function status($id)
    {
        $a2cRequest = A2CRequest::where('user_id', auth()->id())->findOrFail($id);

        if ($a2cRequest->status === 'completed') {
            return view('a2c.completed', ['request' => $a2cRequest]);
        }

        return view('a2c.status', ['request' => $a2cRequest]);
    }
}
