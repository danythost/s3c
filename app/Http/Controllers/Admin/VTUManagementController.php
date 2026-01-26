<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataPlan;
use App\Services\VTU\EpinsVTUService;
use Illuminate\Http\Request;

class VTUManagementController extends Controller
{
    public function plans()
    {
        $plans = DataPlan::orderBy('network')->orderBy('provider_price')->paginate(20);
        return view('admin.vtu.plans', compact('plans'));
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

    public function syncEpins(\App\Actions\VTU\SyncEpinsDataPlans $syncAction)
    {
        try {
            $syncAction->execute();
            return back()->with('success', 'Data plans synced successfully from EPINS.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
