<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiLog;
use Illuminate\Http\Request;

class ApiLogController extends Controller
{
    /**
     * Display a listing of the API logs.
     */
    public function index(Request $request)
    {
        $query = ApiLog::latest();

        // Search Filters
        if ($request->filled('ref')) {
            $search = $request->ref;
            $query->where(function($q) use ($search) {
                // Search inside the JSON payloads
                $q->where('request_payload', 'like', "%{$search}%")
                  ->orWhere('response_payload', 'like', "%{$search}%");
            });
        }

        if ($request->filled('provider')) {
            $query->where('provider', $request->provider);
        }

        if ($request->filled('status')) {
            $query->where('status_code', $request->status);
        }

        $logs = $query->paginate(20)->withQueryString();

        return view('admin.logs.api', compact('logs'));
    }

    /**
     * Display the specified resource.
     */
    public function show(ApiLog $log)
    {
        return view('admin.logs.show_api', compact('log'));
    }
}
