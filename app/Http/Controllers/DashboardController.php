<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Member;
use App\Models\Group;
use App\Models\Contribution;
use App\Models\Event;
use App\Models\Asset;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Core Statistics
        $totalMembers = Member::count();
        $totalGroups = Group::count();
        $totalIncome = Contribution::sum('amount');
        $totalExpenses = Expense::sum('amount');
        $netBalance = $totalIncome - $totalExpenses;
        
        // Growth Trends (Last 6 Months)
        $memberGrowth = Member::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();
            
        // Finance Trends
        $incomeTrends = Contribution::selectRaw('MONTH(contribution_date) as month, SUM(amount) as total')
            ->where('contribution_date', '>=', now()->startOfYear())
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Upcoming Events
        $upcomingEvents = Event::where('event_date', '>=', now()->toDateString())
            ->orderBy('event_date')
            ->orderBy('event_time')
            ->take(5)
            ->get();

        // Recent Contributions
        $recentContributions = Contribution::with('member')
            ->latest()
            ->take(5)
            ->get();

        // Prepare chart data (ensure 12 months for finance)
        $chartMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $financeData = array_fill(1, 12, 0);
        foreach ($incomeTrends as $m => $total) $financeData[$m] = (float)$total;

        return view('dashboard', compact(
            'totalMembers', 'totalGroups', 'totalIncome', 'totalExpenses', 'netBalance',
            'upcomingEvents', 'recentContributions', 'financeData', 'chartMonths'
        ));
    }
}
