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
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Member Dashboard
        if ($user->member) {
            $member = $user->member;
            
            // --- CONTRIBUTION TRENDS ---
            $trendFilter = $request->get('trend_filter', 'month');
            $trendQuery = $member->contributions();
            $trendData = [];
            $trendLabels = [];

            if ($trendFilter == 'week') {
                // Last 7 days including today
                for ($i = 6; $i >= 0; $i--) {
                    $date = now()->subDays($i);
                    $trendLabels[] = $date->format('D'); // Mon, Tue...
                    $trendData[$date->format('Y-m-d')] = 0;
                }

                $results = $member->contributions()
                    ->where('contribution_date', '>=', now()->subDays(6)->startOfDay())
                    ->select(DB::raw('SUM(amount) as total'), DB::raw("DATE_FORMAT(contribution_date, '%Y-%m-%d') as day"))
                    ->groupBy('day')
                    ->get();

                foreach ($results as $r) {
                    if (isset($trendData[$r->day])) $trendData[$r->day] = (float)$r->total;
                }
                $trendData = array_values($trendData);

            } elseif ($trendFilter == 'year') {
                // All 12 months of current year
                $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                $trendLabels = $months;
                $trendData = array_fill(0, 12, 0);

                $results = $member->contributions()
                    ->whereYear('contribution_date', now()->year)
                    ->select(DB::raw('SUM(amount) as total'), DB::raw("MONTH(contribution_date) as month"))
                    ->groupBy('month')
                    ->get();

                foreach ($results as $r) {
                    $trendData[$r->month - 1] = (float)$r->total;
                }
            } else { // Default: Last 6 Months
                $tempData = [];
                for ($i = 5; $i >= 0; $i--) {
                    $date = now()->subMonths($i);
                    $trendLabels[] = $date->format('M');
                    $tempData[$date->format('Y-m')] = 0;
                }

                $results = $member->contributions()
                    ->where('contribution_date', '>=', now()->subMonths(5)->startOfMonth())
                    ->select(DB::raw('SUM(amount) as total'), DB::raw("DATE_FORMAT(contribution_date, '%Y-%m') as ym"))
                    ->groupBy('ym')
                    ->get();

                foreach ($results as $r) {
                    if (isset($tempData[$r->ym])) $tempData[$r->ym] = (float)$r->total;
                }
                $trendData = array_values($tempData);
            }

            // --- CONTRIBUTION DISTRIBUTION ---
            $distFilter = $request->get('dist_filter', 'year');
            $distQuery = $member->contributions()
                ->select('contribution_type', DB::raw('SUM(amount) as total'));
            
            if ($distFilter == 'month') {
                $distQuery->whereMonth('contribution_date', now()->month)
                          ->whereYear('contribution_date', now()->year);
            } else {
                $distQuery->whereYear('contribution_date', now()->year);
            }
            
            $contributionTypes = $distQuery->groupBy('contribution_type')->get();

            $totalContributed = $member->contributions()->sum('amount');
            $recentContributions = $member->contributions()->latest()->limit(5)->get();
            $upcomingEvents = \App\Models\Event::where('event_date', '>=', now())
                ->orderBy('event_date')
                ->limit(3)
                ->get();

            // --- GROUP LEADERSHIP ---
            $ledGroups = Group::where('chairperson_id', $member->id)
                ->orWhere('secretary_id', $member->id)
                ->orWhere('accountant_id', $member->id)
                ->get();

            // --- ANNOUNCEMENTS ---
            $announcements = \App\Models\Communication::where(function($query) use ($member) {
                    $query->where('recipient_type', 'all')
                        ->orWhere(function($q) use ($member) {
                            $q->where('recipient_type', 'group')
                              ->whereIn('group_id', $member->groups->pluck('id'));
                        });
                })
                ->where('status', 'sent')
                ->latest()
                ->limit(5)
                ->get();

            return view('member.dashboard', compact(
                'member', 
                'trendLabels', 
                'trendData', 
                'contributionTypes', 
                'totalContributed', 
                'recentContributions',
                'upcomingEvents',
                'ledGroups',
                'announcements'
            ));
        }

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

        // --- COMMUNITY ANALYTICS (FOR ADMINS) ---
        $topGroups = Group::withCount('members')
            ->orderBy('members_count', 'desc')
            ->take(5)
            ->get();
            
        $communityCollections = \App\Models\GroupMeeting::sum('total_collected');
        
        $recentGroupActivity = \App\Models\GroupMeeting::with('group')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalMembers', 'totalGroups', 'totalIncome', 'totalExpenses', 'netBalance',
            'upcomingEvents', 'recentContributions', 'financeData', 'chartMonths',
            'topGroups', 'communityCollections', 'recentGroupActivity'
        ));
    }
}
