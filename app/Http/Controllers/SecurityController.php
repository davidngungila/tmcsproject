<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class SecurityController extends Controller
{
    /**
     * Display Security Settings.
     */
    public function index()
    {
        $settings = SystemSetting::where('group', 'security')->get();
        return view('settings.security.index', compact('settings'));
    }

    /**
     * Update Security Settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
        ]);

        foreach ($validated['settings'] as $key => $value) {
            SystemSetting::set($key, $value);
        }

        // Handle Maintenance Mode
        if (isset($validated['settings']['maintenance_mode'])) {
            if ($validated['settings']['maintenance_mode'] == '1') {
                Artisan::call('down');
            } else {
                Artisan::call('up');
            }
        }

        return back()->with('success', 'Security settings updated successfully.');
    }

    /**
     * Force logout all users except current.
     */
    public function forceLogoutAll()
    {
        // This is tricky without database sessions, but we can clear the sessions table if it exists
        // Or set a 'force_logout_at' field on users table and check in middleware
        User::where('id', '!=', Auth::id())->update(['remember_token' => null]);
        
        return back()->with('success', 'All other users have been forced to logout.');
    }

    /**
     * Block IP address.
     */
    public function blockIp(Request $request)
    {
        $request->validate(['ip' => 'required|ip']);
        
        $blockedIps = SystemSetting::get('blocked_ips', []);
        $blockedIps[] = $request->ip;
        SystemSetting::set('blocked_ips', array_unique($blockedIps));

        return back()->with('success', "IP {$request->ip} blocked successfully.");
    }
}
