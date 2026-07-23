<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Member;
use App\Models\Group;
use App\Models\MemberCategory;
use App\Models\Role;
use Illuminate\Support\Str;
use App\Mail\PasswordResetMailable;
use App\Jobs\SendSmsJob;

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
     * Show the forgot password form.
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send a new password to the user's email.
     */
    public function sendResetPasswordEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = User::where('email', $request->email)->first();

        // 1. Generate a random secure password
        $newPassword = Str::random(10);

        // 2. Update user record
        $user->update([
            'password' => Hash::make($newPassword),
            'force_password_change' => true, // Force change on next login
        ]);

        // 3. Send notification email (Queued)
        Mail::to($user->email)->queue(new PasswordResetMailable($user, $newPassword));

        return redirect()->route('login')->with('success', 'A new password has been sent to your email address.');
    }

    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
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
        ]);

        // 1. Create User (Active by default - no approval needed)
        $user = User::create([
            'name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'is_active' => true, // Auto-activate
        ]);

        // 2. Assign 'member' role
        $memberRole = Role::where('name', 'member')->first();
        if ($memberRole) {
            $user->roles()->attach($memberRole->id);
        }

        // 3. Create Member Record with minimal info (to be completed later)
        $member = Member::create([
            'user_id' => $user->id,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'member_type' => 'Regular',
            'registration_date' => now(),
            'is_active' => true, // Auto-activate
            'registration_number' => 'TMCS-' . str_pad(Member::count() + 1, 4, '0', STR_PAD_LEFT),
            'qr_code' => 'QR-' . strtoupper(Str::random(10)),
            'profile_completed' => false, // Flag for profile completion
        ]);

        // 4. Auto-login the user
        Auth::login($user);

        return redirect()->route('member.profile.edit')->with('success', 'Registration successful! Please complete your profile to access all features.');
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
