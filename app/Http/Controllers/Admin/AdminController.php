<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Admin::query()->latest();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->paginate(20)->withQueryString();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create', ['is_admin' => true]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users|unique:admins',
            'email' => 'required|string|email|max:255|unique:users|unique:admins',
            'password' => 'required|string|min:8',
        ]);

        $user = Admin::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        return redirect()->route('admin.admins.show', $user->id)
            ->with('success', "Administrator created successfully.");
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = Admin::find($id);

        if (!$user) {
            abort(404);
        }

        return view('admin.users.show', compact('user'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = Admin::find($id);

        if (!$user) {
            abort(404);
        }

        if (auth()->guard('admin')->id() == $user->id) {
            return back()->with('error', "You cannot delete your own admin account.");
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.admins.index')
            ->with('success', "Admin account for {$name} has been permanently deleted.");
    }
}
