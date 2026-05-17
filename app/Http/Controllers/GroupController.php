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
    public function index()
    {
        $groups = Group::with(['chairperson', 'secretary', 'accountant'])
            ->withCount('members')
            ->paginate(10);
            
        // Transform the collection within the paginator
        $groups->getCollection()->transform(function($group) {
            $group->total_giving = $group->meetings()->sum('total_collected');
            return $group;
        });
            
        $totalGroups = Group::count();
        $totalMembers = Member::count();
        $activeGroups = Group::where('is_active', true)->count();
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

        // Validate that selected programs are not already assigned to another community
        if (!empty($validated['criteria']['program_ids'])) {
            $assignedPrograms = Group::where('type', 'Community')
                ->get()
                ->pluck('criteria.program_ids')
                ->flatten()
                ->filter()
                ->toArray();

            foreach ($validated['criteria']['program_ids'] as $pid) {
                if (in_array($pid, $assignedPrograms)) {
                    $program = Program::find($pid);
                    return back()->withInput()->with('error', "Programme [{$program->code}] is already assigned to another community.");
                }
            }
        }

        $validated['is_active'] = true;
        $validated['formation_date'] = now();
        $validated['created_by'] = Auth::id();

        Group::create($validated);

        return redirect()->route('groups.index')->with('success', 'Group created successfully');
    }

    public function show(Group $group)
    {
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
        $members = Member::all();
        $categories = \App\Models\MemberCategory::where('is_active', true)->get();
        $programs = Program::where('is_active', true)->get();
        return view('groups.edit', compact('group', 'members', 'categories', 'programs'));
    }

    public function update(Request $request, Group $group)
    {
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

        // Validate that selected programs are not already assigned to another community (excluding this one)
        if (!empty($validated['criteria']['program_ids'])) {
            $assignedPrograms = Group::where('type', 'Community')
                ->where('id', '!=', $group->id)
                ->get()
                ->pluck('criteria.program_ids')
                ->flatten()
                ->filter()
                ->toArray();

            foreach ($validated['criteria']['program_ids'] as $pid) {
                if (in_array($pid, $assignedPrograms)) {
                    $program = Program::find($pid);
                    return back()->withInput()->with('error', "Programme [{$program->code}] is already assigned to another community.");
                }
            }
        }

        $validated['updated_by'] = Auth::id();
        $group->update($validated);

        return redirect()->route('groups.index')->with('success', 'Group updated successfully');
    }

    public function destroy(Group $group)
    {
        $group->delete();
        return redirect()->route('groups.index')->with('success', 'Group deleted successfully');
    }

    public function communities()
    {
        $groups = Group::where('type', 'Community')
            ->withCount('members')
            ->paginate(10);
            
        $totalCommunities = Group::where('type', 'Community')->count();
        $totalCommunityMembers = Member::whereHas('groups', function($query) {
            $query->where('type', 'Community');
        })->count();
        
        $communityCollections = GroupMeeting::whereHas('group', function($query) {
            $query->where('type', 'Community');
        })->sum('total_collected');
        
        $activeCommunities = Group::where('type', 'Community')->where('is_active', true)->count();

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
        $group->loadCount('members');
        $totalCollected = $group->meetings()->sum('total_collected');
        $activePlans = $group->plans()->where('status', 'active')->count();
        
        return view('groups.reports.index', compact('group', 'totalCollected', 'activePlans'));
    }

    public function viewReport(Group $group, $type)
    {
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

            $data['genderDist'] = $group->members()
                ->selectRaw('gender, COUNT(*) as count')
                ->groupBy('gender')
                ->get();
            
            $data['membershipTable'] = $group->members()->paginate(15);
        } elseif ($type === 'meetings') {
            $data['title'] = "Meetings & Attendance Report";
            
            $attendanceData = $group->meetings()
                ->selectRaw('MONTH(meeting_date) as month, AVG(total_collected) as avg_collected') // Using collected as proxy for activity if attendance is complex
                ->whereYear('meeting_date', date('Y'))
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->pluck('avg_collected', 'month')
                ->toArray();
            
            foreach ($attendanceData as $month => $avg) {
                $data['chartData'][$month] = round($avg, 0);
            }
            
            $data['recentMeetings'] = $group->meetings()->latest()->limit(10)->get();
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
        }

        return view("groups.reports.view", compact('group', 'data'));
    }
}
