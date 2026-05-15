<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Member;
use App\Models\Event; // Import the Event model
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::with(['chairperson', 'secretary', 'accountant'])
            ->withCount('members')
            ->get()
            ->map(function($group) {
                $group->total_giving = $group->meetings()->sum('total_collected');
                return $group;
            });
            
        $totalGroups = Group::count();
        $totalMembers = Member::count();
        $activeGroups = Group::where('is_active', true)->count();
        $upcomingEvents = Event::where('event_date', '>=', now())->count();
        
        return view('groups.index', compact('groups', 'totalGroups', 'totalMembers', 'activeGroups', 'upcomingEvents'));
    }

    public function create()
    {
        $members = Member::all();
        return view('groups.create', compact('members'));
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
        ]);

        $validated['is_active'] = true;
        $validated['formation_date'] = now();
        $validated['created_by'] = auth()->id();

        Group::create($validated);

        return redirect()->route('groups.index')->with('success', 'Group created successfully');
    }

    public function show(Group $group)
    {
        $group->load([
            'members', 
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
        $actualAttendances = \App\Models\GroupAttendance::whereIn('group_meeting_id', $group->meetings->pluck('id'))
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
        return view('groups.edit', compact('group', 'members'));
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
        ]);

        $validated['updated_by'] = auth()->id();
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
        $groups = Group::where('type', 'Community')->paginate(10);
        return view('groups.communities', compact('groups'));
    }

    public function activities()
    {
        return view('groups.activities');
    }
}
