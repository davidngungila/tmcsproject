<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Member;
use App\Models\Event;
use App\Models\GroupScheduledMessage;
use App\Models\MessageTemplate;
use App\Models\Communication;
use App\Models\GroupAttendance;
use App\Models\GroupMeeting;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    /**
     * Check if the authenticated user is authorized to manage the group.
     */
    private function authorizeGroupAccess(Group $group): void
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */

        // Admins can access everything
        if ($user->hasRole('admin')) {
            return;
        }

        // Check if the user is a leader of the group
        if ($user->member) {
            $memberId = $user->member->id;
            if (
                $group->chairperson_id === $memberId ||
                $group->secretary_id === $memberId ||
                $group->accountant_id === $memberId ||
                $group->leader_id === $memberId
            ) {
                return;
            }
        }

        abort(403, 'You are not authorized to manage this group.');
    }

    public function index()
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */

        $query = Group::with(['chairperson', 'secretary', 'accountant'])
            ->withCount('members');

        // If not admin, only show groups they lead
        if (!$user->hasRole('admin') && $user->member) {
            $memberId = $user->member->id;
            $query->where(function($q) use ($memberId) {
                $q->where('chairperson_id', $memberId)
                    ->orWhere('secretary_id', $memberId)
                    ->orWhere('accountant_id', $memberId)
                    ->orWhere('leader_id', $memberId);
            });
        }

        $groups = $query->paginate(10);
            
        // Transform the collection within the paginator
        $groups->getCollection()->transform(function($group) {
            $group->total_giving = $group->meetings()->sum('total_collected');
            return $group;
        });
            
        // Get statistics based on admin status
        $totalGroupsQuery = Group::query();
        $activeGroupsQuery = Group::where('is_active', true);
        $totalMembersQuery = Member::query();
        
        if (!$user->hasRole('admin') && $user->member) {
            $memberId = $user->member->id;
            $totalGroupsQuery->where(function($q) use ($memberId) {
                $q->where('chairperson_id', $memberId)
                    ->orWhere('secretary_id', $memberId)
                    ->orWhere('accountant_id', $memberId)
                    ->orWhere('leader_id', $memberId);
            });
            $activeGroupsQuery->where(function($q) use ($memberId) {
                $q->where('chairperson_id', $memberId)
                    ->orWhere('secretary_id', $memberId)
                    ->orWhere('accountant_id', $memberId)
                    ->orWhere('leader_id', $memberId);
            });
            // For non-admins, count members in their groups
            $totalMembers = Member::whereHas('groups', function ($q) use ($memberId) {
                $q->where(function ($qq) use ($memberId) {
                    $qq->where('chairperson_id', $memberId)
                        ->orWhere('secretary_id', $memberId)
                        ->orWhere('accountant_id', $memberId)
                        ->orWhere('leader_id', $memberId);
                });
            })->count();
        } else {
            $totalMembers = Member::count();
        }
        
        $totalGroups = $totalGroupsQuery->count();
        $activeGroups = $activeGroupsQuery->count();
        $upcomingEvents = Event::where('event_date', '>=', now())->count();
        
        return view('groups.index', compact('groups', 'totalGroups', 'totalMembers', 'activeGroups', 'upcomingEvents'));
    }

    public function generateFromPrograms()
    {
        $programs = Program::where('is_active', true)->get();
        $count = 0;

        foreach ($programs as $program) {
            $groupName = "SCC " . $program->code;
            
            // Check if group already exists
            if (!Group::where('name', $groupName)->exists()) {
                Group::create([
                    'name' => $groupName,
                    'description' => 'Community for ' . $program->name,
                    'type' => 'Community',
                    'is_active' => true,
                    'formation_date' => now(),
                    'created_by' => Auth::id(),
                    'criteria' => [
                        'program_ids' => [$program->id]
                    ]
                ]);
                $count++;
            }
        }

        return redirect()->route('groups.index')->with('success', "Successfully generated $count communities based on academic programmes.");
    }

    public function create()
    {
        $members = Member::all();
        $categories = \App\Models\MemberCategory::where('is_active', true)->get();
        $programs = Program::where('is_active', true)->get();
        return view('groups.create', compact('members', 'categories', 'programs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:groups,name',
            'description' => 'nullable|string',
            'type' => 'required|string',
            'meeting_day' => 'nullable|string',
            'regular_contribution_amount' => 'nullable|numeric|min:0',
            'chairperson_id' => 'nullable|exists:members,id',
            'secretary_id' => 'nullable|exists:members,id',
            'accountant_id' => 'nullable|exists:members,id',
            'criteria' => 'nullable|array',
            'criteria.program_ids' => 'nullable|array',
            'criteria.program_ids.*' => 'exists:programs,id',
        ]);

        $validated['is_active'] = true;
        $validated['formation_date'] = now();
        $validated['created_by'] = Auth::id();

        Group::create($validated);

        return redirect()->route('groups.index')->with('success', 'Group created successfully');
    }

    public function show(Group $group)
    {
        $this->authorizeGroupAccess($group);
        $group->load([
            'members', 
            'leader',
            'chairperson', 
            'secretary', 
            'accountant',
            'meetings' => function($query) {
                $query->latest()->limit(10);
            },
            'meetings.attendances',
            'plans' => function($query) {
                $query->latest()->limit(5);
            }
        ]);

        // Calculate statistics for reporting
        $totalCollected = $group->meetings()->sum('total_collected');
        
        $totalPossibleAttendances = $group->meetings->count() * $group->members->count();
        $actualAttendances = GroupAttendance::whereIn('group_meeting_id', $group->meetings->pluck('id'))
            ->where('status', 'present')
            ->count();
            
        $attendanceRate = $totalPossibleAttendances > 0 
            ? round(($actualAttendances / $totalPossibleAttendances) * 100, 1) 
            : 0;

        $monthlyCollections = $group->meetings()
            ->selectRaw('MONTH(meeting_date) as month, SUM(total_collected) as total')
            ->whereYear('meeting_date', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('groups.show', compact('group', 'totalCollected', 'attendanceRate', 'monthlyCollections'));
    }

    public function edit(Group $group)
    {
        $this->authorizeGroupAccess($group);
        $members = Member::all();
        $categories = \App\Models\MemberCategory::where('is_active', true)->get();
        $programs = Program::where('is_active', true)->get();
        return view('groups.edit', compact('group', 'members', 'categories', 'programs'));
    }

    public function update(Request $request, Group $group)
    {
        $this->authorizeGroupAccess($group);
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:groups,name,' . $group->id,
            'description' => 'nullable|string',
            'type' => 'required|string',
            'meeting_day' => 'nullable|string',
            'regular_contribution_amount' => 'nullable|numeric|min:0',
            'chairperson_id' => 'nullable|exists:members,id',
            'secretary_id' => 'nullable|exists:members,id',
            'accountant_id' => 'nullable|exists:members,id',
            'is_active' => 'required|boolean',
            'criteria' => 'nullable|array',
            'criteria.program_ids' => 'nullable|array',
            'criteria.program_ids.*' => 'exists:programs,id',
        ]);

        $validated['updated_by'] = Auth::id();
        $group->update($validated);

        return redirect()->route('groups.index')->with('success', 'Group updated successfully');
    }

    public function destroy(Group $group)
    {
        $this->authorizeGroupAccess($group);
        $group->delete();
        return redirect()->route('groups.index')->with('success', 'Group deleted successfully');
    }

    public function communities()
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */

        $query = Group::where('type', 'Community')
            ->withCount('members');

        // If not admin, only show communities they lead
        if (!$user->hasRole('admin') && $user->member) {
            $memberId = $user->member->id;
            $query->where(function($q) use ($memberId) {
                $q->where('chairperson_id', $memberId)
                    ->orWhere('secretary_id', $memberId)
                    ->orWhere('accountant_id', $memberId)
                    ->orWhere('leader_id', $memberId);
            });
        }

        $groups = $query->paginate(10);
            
        // Get total communities based on admin status
        $totalCommunitiesQuery = Group::where('type', 'Community');
        $activeCommunitiesQuery = Group::where('type', 'Community')->where('is_active', true);
        $totalCommunityMembersQuery = Member::whereHas('groups', function($query) {
            $query->where('type', 'Community');
        });
        
        if (!$user->hasRole('admin') && $user->member) {
            $memberId = $user->member->id;
            $totalCommunitiesQuery->where(function($q) use ($memberId) {
                $q->where('chairperson_id', $memberId)
                    ->orWhere('secretary_id', $memberId)
                    ->orWhere('accountant_id', $memberId)
                    ->orWhere('leader_id', $memberId);
            });
            $activeCommunitiesQuery->where(function($q) use ($memberId) {
                $q->where('chairperson_id', $memberId)
                    ->orWhere('secretary_id', $memberId)
                    ->orWhere('accountant_id', $memberId)
                    ->orWhere('leader_id', $memberId);
            });
            // Filter community members to only those in the user's communities
            $totalCommunityMembersQuery = Member::whereHas('groups', function($q) use ($memberId) {
                $q->where('type', 'Community')
                    ->where(function($qq) use ($memberId) {
                        $qq->where('chairperson_id', $memberId)
                            ->orWhere('secretary_id', $memberId)
                            ->orWhere('accountant_id', $memberId)
                            ->orWhere('leader_id', $memberId);
                    });
            });
        }
        
        $totalCommunities = $totalCommunitiesQuery->count();
        $totalCommunityMembers = $totalCommunityMembersQuery->count();
        
        $communityCollections = GroupMeeting::whereHas('group', function($query) use ($user) {
            $query->where('type', 'Community');
            if (!$user->hasRole('admin') && $user->member) {
                $memberId = $user->member->id;
                $query->where(function($qq) use ($memberId) {
                    $qq->where('chairperson_id', $memberId)
                        ->orWhere('secretary_id', $memberId)
                        ->orWhere('accountant_id', $memberId)
                        ->orWhere('leader_id', $memberId);
                });
            }
        })->sum('total_collected');
        
        $activeCommunities = $activeCommunitiesQuery->count();

        return view('groups.communities', compact(
            'groups', 
            'totalCommunities', 
            'totalCommunityMembers', 
            'communityCollections', 
            'activeCommunities'
        ));
    }

    public function activities()
    {
        return view('groups.activities');
    }

    public function assignLeadership(Request $request, Group $group)
    {
        $this->authorizeGroupAccess($group);
        $validated = $request->validate([
            'role' => 'required|string|in:leader_id,chairperson_id,secretary_id,accountant_id',
            'member_id' => 'required|exists:members,id'
        ]);

        try {
            $group->update([
                $validated['role'] => $validated['member_id']
            ]);

            return back()->with('success', 'Leadership role assigned successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to assign leadership: ' . $e->getMessage());
        }
    }

    public function reports(Group $group)
    {
        $this->authorizeGroupAccess($group);
        $group->loadCount('members');
        $totalCollected = $group->meetings()->sum('total_collected');
        $activePlans = $group->plans()->where('status', 'active')->count();
        
        return view('groups.reports.index', compact('group', 'totalCollected', 'activePlans'));
    }

    public function viewReport(Group $group, $type)
    {
        $this->authorizeGroupAccess($group);
        $group->load(['members', 'meetings.attendances', 'plans']);

        $data = [];
        $data['type'] = $type;
        $data['title'] = ucfirst($type) . " Report";

        // Common Data
        $data['totalMembers'] = $group->members->count();
        $data['totalCollected'] = $group->meetings->sum('total_collected');
        
        // Initialize chart data with zeros
        $data['chartData'] = array_fill(1, 12, 0);

        if ($type === 'financial') {
            $data['title'] = "Financial Performance Report";
            $data['avgMeetingGiving'] = $group->meetings()->avg('total_collected') ?: 0;
            $monthlyData = $group->meetings()
                ->selectRaw('MONTH(meeting_date) as month, SUM(total_collected) as total')
                ->whereYear('meeting_date', date('Y'))
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->pluck('total', 'month')
                ->toArray();
            
            foreach ($monthlyData as $month => $total) {
                $data['chartData'][$month] = $total;
            }

            $data['recentTransactions'] = $group->meetings()
                ->latest('meeting_date')
                ->limit(10)
                ->get();
        } elseif ($type === 'administrative') {
            $data['title'] = "Administrative & Membership Report";
            
            // Fix SQL error by using direct DB query on pivot table
            $growthData = DB::table('member_groups')
                ->selectRaw('MONTH(join_date) as month, COUNT(*) as count')
                ->where('group_id', $group->id)
                ->whereYear('join_date', date('Y'))
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->pluck('count', 'month')
                ->toArray();
            
            foreach ($growthData as $month => $count) {
                $data['chartData'][$month] = $count;
            }

            $data['genderDist'] = DB::table('members')
                ->join('member_groups', 'members.id', '=', 'member_groups.member_id')
                ->selectRaw('members.gender, COUNT(*) as count')
                ->where('member_groups.group_id', $group->id)
                ->groupBy('members.gender')
                ->get();
            
            $data['membershipTable'] = $group->members()->paginate(15);
            
            // New real stats for KPI cards
            $thisMonth = date('Y-m');
            $data['newThisMonth'] = DB::table('member_groups')
                ->where('group_id', $group->id)
                ->whereRaw('DATE_FORMAT(join_date, "%Y-%m") = ?', [$thisMonth])
                ->count();
            $data['activePlans'] = $group->plans()->where('status', 'active')->count();
        } elseif ($type === 'meetings') {
            $data['title'] = "Meetings & Attendance Report";
            
            // Real attendance data
            $attendanceData = $group->meetings()
                ->selectRaw('MONTH(meeting_date) as month, COUNT(*) as count')
                ->whereYear('meeting_date', date('Y'))
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->pluck('count', 'month')
                ->toArray();
            
            foreach ($attendanceData as $month => $count) {
                $data['chartData'][$month] = $count;
            }
            
            $data['recentMeetings'] = $group->meetings()->latest()->limit(10)->get();
            
            // New real stats for KPI cards
            $totalPossible = 0;
            $totalPresent = 0;
            foreach ($group->meetings as $meeting) {
                $totalPossible += $group->members->count();
                $totalPresent += $meeting->attendances()->where('status', 'present')->count();
            }
            $data['avgAttendanceRate'] = $totalPossible > 0 ? round(($totalPresent / $totalPossible) * 100, 1) : 0;
            
            // Attendance status distribution for donut chart
            $data['attendanceDist'] = GroupAttendance::whereIn('group_meeting_id', $group->meetings->pluck('id'))
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->get();
        } elseif ($type === 'communication') {
            $data['title'] = "Communication & Engagement Report";
            $data['scheduledCount'] = GroupScheduledMessage::where('group_id', $group->id)->count();
            $data['templateCount'] = MessageTemplate::where('group_id', $group->id)->count();
            
            $messageData = Communication::where('group_id', $group->id)
                ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->whereYear('created_at', date('Y'))
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->pluck('count', 'month')
                ->toArray();

            foreach ($messageData as $month => $count) {
                $data['chartData'][$month] = $count;
            }

            $data['recentMessages'] = Communication::where('group_id', $group->id)
                ->latest()
                ->limit(10)
                ->get();
                
            // New real stats for KPI cards
            $data['totalMessages'] = Communication::where('group_id', $group->id)->count();
            
            // Message type distribution for donut chart
            $data['messageDist'] = Communication::where('group_id', $group->id)
                ->selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->get();
        }

        return view("groups.reports.view", compact('group', 'data'));
    }
}
