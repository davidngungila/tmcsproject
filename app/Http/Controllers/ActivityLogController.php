<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('action', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $logs = $query->latest()->paginate(20);
        return view('activity_logs.index', compact('logs'));
    }
}
