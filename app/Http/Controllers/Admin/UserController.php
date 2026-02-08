<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $model = User::class;
        $with = ['wallet'];

        if ($request->status === 'admins') {
            $model = Admin::class;
            $with = [];
        }

        $query = $model::query()->latest();
        if ($with) {
            $query->with($with);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            switch ($request->status) {
                case 'active':
                    $query->where('is_active', true);
                    break;
                case 'suspended':
                    $query->where('is_active', false);
                    break;
            }
        }

        $users = $query->paginate(20)->withQueryString();
        return view('admin.users.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::find($id);
        
        if (!$user) {
            $user = Admin::find($id);
        }

        if (!$user) {
            abort(404);
        }

        $user->load(['wallet', 'transactions' => function($q) {
            $q->latest()->take(10);
        }]);
        
        return view('admin.users.show', compact('user'));
    }

    public function toggleStatus($id)
    {
        $user = User::find($id);
        
        if (!$user) {
            $user = Admin::find($id);
            if ($user) {
                return back()->with('error', "Admin status cannot be toggled from here.");
            }
        }

        if (!$user) {
            abort(404);
        }

        $user->update(['is_active' => !$user->is_active]);
        
        $status = $user->is_active ? 'activated' : 'suspended';
        return back()->with('success', "User has been {$status}.");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
