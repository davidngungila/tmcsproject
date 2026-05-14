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
        $groups = Group::withCount('members')->paginate(10);
        $totalGroups = Group::count();
        $totalMembers = Member::count(); // Calculate total members
        $activeGroups = Group::where('is_active', true)->count(); // Calculate active groups
        $upcomingEvents = Event::where('event_date', '>=', now())->count(); // Calculate upcoming events
        return view('groups.index', compact('groups', 'totalGroups', 'totalMembers', 'activeGroups', 'upcomingEvents'));
    }

    public function create()
    {
        return view('groups.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:groups,name',
            'description' => 'nullable|string',
            'type' => 'required|string',
            'meeting_day' => 'nullable|string',
            'meeting_time' => 'nullable|string',
        ]);

        $validated['is_active'] = true;
        $validated['formation_date'] = now();

        Group::create($validated);

        return redirect()->route('groups.index')->with('success', 'Group created successfully');
    }

    public function show(Group $group)
    {
        $group->load('members');
        return view('groups.show', compact('group'));
    }

    public function edit(Group $group)
    {
        return view('groups.edit', compact('group'));
    }

    public function update(Request $request, Group $group)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:groups,name,' . $group->id,
            'description' => 'nullable|string',
            'type' => 'required|string',
            'meeting_day' => 'nullable|string',
            'meeting_time' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

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
