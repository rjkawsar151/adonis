<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('id', 'desc')->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = [
            'super_admin' => 'Super Admin (Full System & User Control)',
            'admin' => 'Admin (Site & Operations Manager)',
            'hr' => 'HR Manager (Careers & Applications Access)',
            'content_editor' => 'Content Editor (Blog & Articles Access Only)',
        ];

        if (!Auth::user()->isSuperAdmin()) {
            unset($roles['super_admin']);
        }

        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $allowedRoles = ['admin', 'hr', 'content_editor'];
        if (Auth::user()->isSuperAdmin()) {
            $allowedRoles[] = 'super_admin';
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in($allowedRoles)],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'Admin user created successfully.');
    }

    public function edit(User $user)
    {
        if ($user->isSuperAdmin() && !Auth::user()->isSuperAdmin()) {
            return redirect()->route('admin.users.index')->with('error', 'Only a Super Admin can modify a Super Admin account.');
        }

        $roles = [
            'super_admin' => 'Super Admin (Full System & User Control)',
            'admin' => 'Admin (Site & Operations Manager)',
            'hr' => 'HR Manager (Careers & Applications Access)',
            'content_editor' => 'Content Editor (Blog & Articles Access Only)',
        ];

        if (!Auth::user()->isSuperAdmin()) {
            unset($roles['super_admin']);
        }

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->isSuperAdmin() && !Auth::user()->isSuperAdmin()) {
            return redirect()->route('admin.users.index')->with('error', 'Only a Super Admin can modify a Super Admin account.');
        }

        $allowedRoles = ['admin', 'hr', 'content_editor'];
        if (Auth::user()->isSuperAdmin()) {
            $allowedRoles[] = 'super_admin';
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', Rule::in($allowedRoles)],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if ($user->isSuperAdmin() && !Auth::user()->isSuperAdmin()) {
            return back()->with('error', 'Only a Super Admin can delete a Super Admin account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
