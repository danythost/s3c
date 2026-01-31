<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\AuditLog;
use App\Models\Provider;
use App\Models\Setting;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class SystemConfigController extends Controller
{
    public function index()
    {
        $providers = Provider::all();
        $settings = Setting::all()->groupBy('group');
        $auditLogs = AuditLog::with('user')->latest()->paginate(20, ['*'], 'audit_page');

        // Maintenance Data
        $maintenance = [
            'system' => [
                'php_version' => PHP_VERSION,
                'db_connection' => DB::connection()->getDatabaseName(),
                'server_time' => now()->toDateTimeString(),
                'environment' => app()->environment(),
            ],
            'queue' => [
                'pending' => DB::table('jobs')->count(),
                'failed' => DB::table('failed_jobs')->count(),
            ],
            'failed_jobs_list' => DB::table('failed_jobs')->orderBy('failed_at', 'desc')->take(10)->get(),
        ];

        return view('admin.settings.index', compact('providers', 'settings', 'auditLogs', 'maintenance'));
    }

    public function clearCache(Request $request)
    {
        $type = $request->type ?? 'all';

        try {
            switch ($type) {
                case 'config':
                    Artisan::call('config:clear');
                    break;
                case 'view':
                    Artisan::call('view:clear');
                    break;
                case 'route':
                    Artisan::call('route:clear');
                    break;
                case 'application':
                    Cache::flush();
                    break;
                default:
                    Artisan::call('optimize:clear');
                    Cache::flush();
                    break;
            }

            AuditLog::log('clear_cache', "Cleared {$type} cache.");
            return back()->with('success', ucfirst($type) . ' cache cleared successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error clearing cache: ' . $e->getMessage());
        }
    }

    public function manageFailedJobs(Request $request)
    {
        $action = $request->action;

        try {
            if ($action === 'retry_all') {
                Artisan::call('queue:retry', ['id' => ['all']]);
                $message = "All failed jobs queued for retry.";
            } elseif ($action === 'delete_all') {
                Artisan::call('queue:flush');
                $message = "All failed jobs deleted.";
            }

            AuditLog::log('manage_failed_jobs', $message);
            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Error managing jobs: ' . $e->getMessage());
        }
    }

    public function updateProvider(Request $request, Provider $provider)
    {
        $request->validate([
            'name' => 'required|string',
            'config' => 'nullable|array',
            'is_active' => 'required|boolean',
        ]);

        $provider->update($request->only('name', 'config', 'is_active'));

        AuditLog::log('update_provider', "Updated configuration for provider: {$provider->name}");

        return back()->with('success', 'Provider settings updated.');
    }

    public function updatePricing(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
        ]);

        foreach ($request->settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value, 'group' => 'pricing']);
        }

        AuditLog::log('update_pricing', "Updated global pricing rules.");

        return back()->with('success', 'Pricing settings updated.');
    }

    public function storeSetting(Request $request)
    {
        $request->validate([
            'key' => 'required|string|unique:settings,key',
            'value' => 'nullable|string',
            'group' => 'required|string',
        ]);

        Setting::create($request->all());

        return back()->with('success', 'Setting added.');
    }
}
