<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuthenticationLog;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ActivityMonitoringController extends Controller
{
    /**
     * Display the Activity Monitoring Dashboard.
     */
    public function dashboard()
    {
        // Live active users (seen in the last 5 minutes)
        $activeUsersCount = User::where('last_login_at', '>=', now()->subMinutes(5))->count();
        $activeUsers = User::where('last_login_at', '>=', now()->subMinutes(5))->take(10)->get();

        // Recent logins
        $recentLogins = AuthenticationLog::with('user')->where('login_successful', true)->latest()->take(10)->get();

        // Recent actions feed
        $recentActions = ActivityLog::with('user')->latest()->take(15)->get();

        // System alerts (failed logins, suspicious activity)
        $failedLogins = AuthenticationLog::where('login_successful', false)->latest()->take(5)->get();
        $suspiciousLogins = AuthenticationLog::where('is_suspicious', true)->latest()->take(5)->get();

        return view('settings.monitoring.dashboard', compact(
            'activeUsersCount', 'activeUsers', 'recentLogins', 'recentActions', 'failedLogins', 'suspiciousLogins'
        ));
    }

    /**
     * Display Authentication Logs.
     */
    public function authLogs(Request $request)
    {
        $query = AuthenticationLog::with('user');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('status')) {
            $query->where('login_successful', $request->status == 'success');
        }

        $logs = $query->latest()->paginate(25);
        $users = User::all();

        return view('settings.monitoring.auth_logs', compact('logs', 'users'));
    }

    /**
     * Display Action Logs.
     */
    public function actionLogs(Request $request)
    {
        $query = ActivityLog::with('user');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        $logs = $query->latest()->paginate(25);
        $users = User::all();
        $modules = ActivityLog::distinct()->pluck('module');

        return view('settings.monitoring.action_logs', compact('logs', 'users', 'modules'));
    }
}
