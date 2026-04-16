<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    /**
     * Submit a problem report.
     */
    public function report(Request $request)
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);

        // For now, we'll log it and return success. 
        // In a production app, we would save to a SupportTicket model.
        \Log::info("PROBLEM REPORT from User ID: " . $request->user()->id, $validated);

        return response()->json([
            'success' => true,
            'message' => 'Your report has been logged. Our team will review it shortly.',
        ]);
    }
}
