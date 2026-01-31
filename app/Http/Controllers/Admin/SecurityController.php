<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\LoginHistory;
use App\Models\AuditLog;
use App\Models\ApiLog;

class SecurityController extends Controller
{
    public function index(Request $request)
    {
        $loginHistory = LoginHistory::with('user')->latest()->paginate(20, ['*'], 'login_page');
        $auditLogs = AuditLog::with('user')->latest()->paginate(20, ['*'], 'audit_page');
        $apiLogs = ApiLog::latest()->take(50)->get();

        return view('admin.security.index', compact('loginHistory', 'auditLogs', 'apiLogs'));
    }
}
