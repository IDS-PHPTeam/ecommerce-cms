<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\LogsAudit;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    use LogsAudit;
    /**
     * Display a listing of admin users.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $admins = User::whereIn('role', ['admin', 'manager', 'editor'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new admin.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admins.create');
    }

    /**
     * Store a newly created admin in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:admin,manager,editor',
        ]);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        $this->logAudit('created', $user, "Admin created: {$user->name} ({$user->email})");

        return redirect()->route('admins.index')
            ->with('success', 'Admin created successfully.');
    }

    /**
     * Show the form for editing the specified admin.
     *
     * @param  \App\Models\User  $admin
     * @return \Illuminate\View\View
     */
    public function edit(User $admin)
    {
        return view('admins.edit', compact('admin'));
    }

    /**
     * Update the specified admin in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $admin
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $admin)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $admin->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|string|in:admin,manager,editor',
        ]);

        $admin->first_name = $validated['first_name'];
        $admin->last_name = $validated['last_name'];
        $admin->name = $validated['first_name'] . ' ' . $validated['last_name'];
        $admin->email = $validated['email'];
        $admin->role = $validated['role'];

        if ($request->filled('password')) {
            $admin->password = Hash::make($validated['password']);
        }

        $admin->save();

        $oldValues = $this->getOldValues($admin, ['first_name', 'last_name', 'email', 'role']);
        $newValues = $this->getNewValues($validated, ['first_name', 'last_name', 'email', 'role']);

        $this->logAudit('updated', $admin, "Admin updated: {$admin->name} ({$admin->email})", $oldValues, $newValues);

        return redirect()->route('admins.index')
            ->with('success', 'Admin updated successfully.');
    }

    /**
     * Remove the specified admin from storage.
     *
     * @param  \App\Models\User  $admin
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $admin)
    {
        $adminName = $admin->name;
        $adminEmail = $admin->email;

        $this->logAudit('deleted', $admin, "Admin deleted: {$adminName} ({$adminEmail})");

        $admin->roles()->detach();
        $admin->delete();

        return redirect()->route('admins.index')
            ->with('success', 'Admin deleted successfully.');
    }
}

