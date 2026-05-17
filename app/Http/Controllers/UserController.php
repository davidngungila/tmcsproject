<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Mail\PasswordResetMailable;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%");
            });
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('roles.id', $request->role);
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status == 'active');
        }

        $users = $query->latest()->paginate(15);
        $roles = Role::all();
        
        return view('users.index', compact('users', 'roles'));
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load(['roles.permissions', 'authenticationLogs', 'activityLogs']);
        return view('users.show', compact('user'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,id',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'is_active' => true,
        ]);

        $user->roles()->attach($request->role);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $userRole = $user->roles->first()?->id;
        return view('users.edit', compact('user', 'roles', 'userRole'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|exists:roles,id',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'required|boolean',
            'force_password_change' => 'required|boolean',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'is_active' => $request->is_active,
            'force_password_change' => $request->force_password_change,
        ]);

        if ($request->password) {
            $request->validate(['password' => 'confirmed|min:8']);
            $user->update(['password' => Hash::make($request->password)]);
        }

        $user->roles()->sync([$request->role]);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Reset user password to a randomly generated one.
     */
    public function resetPassword(Request $request, User $user)
    {
        // 1. Generate a random secure password
        $newPassword = Str::random(10);

        // 2. Update user record
        $user->update([
            'password' => Hash::make($newPassword),
            'force_password_change' => true, // Force change on next login
        ]);

        // 3. Send notification email (Queued)
        Mail::to($user->email)->queue(new PasswordResetMailable($user, $newPassword));

        return back()->with('success', "Password for {$user->name} has been reset successfully and notification sent to their email.");
    }

    /**
     * Toggle user active status.
     */
    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'activated' : 'deactivated';
        
        return back()->with('success', "User account has been {$status}.");
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }
        
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
