<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Member;
use App\Models\Group;
use App\Models\MemberCategory;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        $groups = Group::where('is_active', true)->get();
        $categories = MemberCategory::where('is_active', true)->get();
        return view('auth.register', compact('groups', 'categories'));
    }

    /**
     * Handle a registration request.
     */
    public function register(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|unique:members,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'category_id' => 'required|exists:member_categories,id',
            'gender' => 'required|string|in:Male,Female,Other',
            'date_of_birth' => 'required|date',
            'address' => 'required|string',
            'groups' => 'nullable|array',
            'groups.*' => 'exists:groups,id',
        ]);

        // 1. Create User (Inactive by default for approval)
        $user = User::create([
            'name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'is_active' => false, // Requires approval
        ]);

        // 2. Assign 'member' role
        $memberRole = \App\Models\Role::where('name', 'member')->first();
        if ($memberRole) {
            $user->roles()->attach($memberRole->id);
        }

        // 3. Create Member Record
        $category = MemberCategory::find($request->category_id);
        $member = Member::create([
            'user_id' => $user->id,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'category_id' => $request->category_id,
            'member_type' => $category->name,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
            'registration_date' => now(),
            'is_active' => false, // Requires approval
            'registration_number' => 'PENDING-' . strtoupper(Str::random(6)),
            'qr_code' => 'QR-' . strtoupper(Str::random(10)),
        ]);

        // 4. Join Groups (if selected)
        if ($request->has('groups')) {
            foreach ($request->groups as $groupId) {
                $member->groups()->attach($groupId, [
                    'join_date' => now(),
                    'is_active' => false, // Group membership also pending approval
                ]);
            }
        }

        return redirect()->route('login')->with('success', 'Registration successful! Your account is pending administrator approval.');
    }

    /**
     * Handle a login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            // Check if user is active
            if (!Auth::user()->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account is pending approval. Please contact the administrator.',
                ]);
            }

            $request->session()->regenerate();
            
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }
}
