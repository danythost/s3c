<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataPlan;
use App\Models\AirtimeControl;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;

class VTUManagementController extends Controller
{
    public function plans(Request $request)
    {
        $query = DataPlan::query();

        if ($request->filled('network')) {
            $query->where('network', $request->network);
        }

        if ($request->filled('provider')) {
            $query->where('provider', $request->provider);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        $plans = $query->orderBy('network')->orderBy('provider_price')->paginate(20)->withQueryString();
        $networks = DataPlan::select('network')->distinct()->pluck('network');

        return view('admin.vtu.plans', compact('plans', 'networks'));
    }

    public function storePlan(Request $request)
    {
        $validated = $request->validate([
            'network' => 'required|string',
            'provider' => 'required|string',
            'name' => 'required|string',
            'code' => 'required|string|unique:data_plans,code',
            'provider_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'validity' => 'nullable|string',
        ]);

        $validated['is_active'] = true;
        DataPlan::create($validated);

        return back()->with('success', 'Data plan created successfully.');
    }

    public function importPlans(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        try {
            $file = $request->file('csv_file');
            $handle = fopen($file->getPathname(), 'r');
            $header = fgetcsv($handle); // Skip header

            $count = 0;
            DB::beginTransaction();
            while (($row = fgetcsv($handle)) !== false) {
                // Expected format: network, provider, name, code, provider_price, selling_price, validity
                if (count($row) < 6) continue;

                DataPlan::updateOrCreate(
                    ['code' => $row[3]], // Assume code is unique ID
                    [
                        'network' => $row[0],
                        'provider' => $row[1],
                        'name' => $row[2],
                        'provider_price' => $row[4],
                        'selling_price' => $row[5],
                        'validity' => $row[6] ?? '30 days',
                        'is_active' => true,
                    ]
                );
                $count++;
            }
            DB::commit();
            fclose($handle);

            return back()->with('success', "Imported $count data plans successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('CSV Import Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to import CSV: ' . $e->getMessage());
        }
    }

    public function updatePrice(Request $request, DataPlan $plan)
    {
        $validated = $request->validate([
            'selling_price' => ['required', 'numeric', 'min:0'],
        ]);

        $plan->update($validated);

        return back()->with('success', 'Plan price updated successfully.');
    }

    public function toggleStatus(DataPlan $plan)
    {
        $plan->update(['is_active' => !$plan->is_active]);
        return back()->with('success', 'Plan status toggled.');
    }


    public function airtime()
    {
        $controls = AirtimeControl::orderBy('network')->get();
        // Ensure we have defaults if DB is empty (should satisfy seeder)
        if ($controls->isEmpty()) {
            return redirect()->back()->with('error', 'No airtime controls found. Please run seeder.');
        }
        return view('admin.vtu.airtime', compact('controls'));
    }

    public function updateAirtime(Request $request, AirtimeControl $control)
    {
        $validated = $request->validate([
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'required|numeric|gte:min_amount',
            'commission_percentage' => 'required|numeric|min:0|max:100',
            'is_active' => 'boolean',
        ]);

        // Handle checkbox boolean which might be missing from request if unchecked? 
        // Actually usually frameworks handle this, but let's be safe.
        // Actually for toggle logic we might want a separate toggle method or handle 'is_active' if present.
        // But the user request said "Enable / Disable per network".
        // Let's assume 'is_active' comes from a hidden input or checkbox.
        // Actually, let's make it robust: $request->has('is_active')?
        
        $validated['is_active'] = $request->has('is_active');

        $control->update($validated);

        return back()->with('success', "Updated {$control->network} settings.");
    }
}
