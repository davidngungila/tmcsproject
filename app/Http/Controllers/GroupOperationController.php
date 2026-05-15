<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Member;
use App\Models\GroupMeeting;
use App\Models\GroupAttendance;
use App\Models\GroupPlan;
use App\Models\MessageTemplate;
use App\Models\GroupScheduledMessage;
use App\Models\Contribution;
use App\Services\MessagingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GroupOperationController extends Controller
{
    protected $messagingService;

    public function __construct(MessagingService $messagingService)
    {
        $this->messagingService = $messagingService;
    }

    private function authorizeLeader(Group $group)
    {
        // Admins have access to everything
        if (auth()->user()->hasRole('admin')) {
            return;
        }

        $memberId = auth()->user()->member->id ?? null;
        if (!$memberId) abort(403, 'Unauthorized. No member profile found.');

        if ($group->chairperson_id != $memberId && 
            $group->secretary_id != $memberId && 
            $group->accountant_id != $memberId) {
            abort(403, 'Unauthorized. You are not a leader of this group.');
        }
    }

    public function members(Group $group)
    {
        $this->authorizeLeader($group);
        $group->load('members');
        
        // Statistics for charts
        $memberTypes = $group->members->groupBy('member_type')->map->count();
        $genders = $group->members->groupBy(function($member) {
            return $member->gender ?: 'Unknown';
        })->map->count();
        
        $activeMembers = $group->members->where('pivot.is_active', true)->count();
        $inactiveMembers = $group->members->where('pivot.is_active', false)->count();
        
        $newThisMonth = $group->members()
            ->wherePivot('join_date', '>=', now()->startOfMonth())
            ->count();

        // Get all members not in this group for the "Add Member" dropdown
        $allMembers = Member::whereDoesntHave('groups', function($query) use ($group) {
            $query->where('groups.id', $group->id);
        })->get();

        return view('groups.operations.members', compact(
            'group', 
            'memberTypes', 
            'genders', 
            'activeMembers', 
            'inactiveMembers',
            'newThisMonth',
            'allMembers'
        ));
    }

    public function addMember(Request $request, Group $group)
    {
        $this->authorizeLeader($group);
        $request->validate([
            'member_id' => 'required|exists:members,id'
        ]);

        // Check if already a member (safety)
        if ($group->members()->where('member_id', $request->member_id)->exists()) {
            return back()->with('error', 'Member is already in this group.');
        }

        $group->members()->attach($request->member_id, [
            'join_date' => now(),
            'is_active' => true
        ]);

        return back()->with('success', 'Member added to group successfully.');
    }

    public function removeMember(Request $request, Group $group, Member $member)
    {
        $this->authorizeLeader($group);
        $group->members()->detach($member->id);
        return back()->with('success', 'Member removed from group successfully.');
    }

    public function contributions(Group $group)
    {
        $this->authorizeLeader($group);
        $meetings = $group->meetings()->latest()->paginate(10);
        return view('groups.operations.contributions', compact('group', 'meetings'));
    }

    public function storeContribution(Request $request, Group $group)
    {
        $this->authorizeLeader($group);
        $request->validate([
            'meeting_date' => 'required|date',
            'notes' => 'nullable|string',
            'total_amount' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            GroupMeeting::updateOrCreate(
                ['group_id' => $group->id, 'meeting_date' => $request->meeting_date],
                [
                    'notes' => $request->notes,
                    'total_collected' => $request->total_amount,
                ]
            );

            DB::commit();
            return redirect()->route('groups.operations.contributions', $group->id)->with('success', 'Total giving recorded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to record giving: ' . $e->getMessage());
        }
    }

    public function attendance(Group $group)
    {
        $this->authorizeLeader($group);
        $group->load('members');
        $meetings = $group->meetings()->latest()->paginate(10);
        return view('groups.operations.attendance', compact('group', 'meetings'));
    }

    public function storeAttendance(Request $request, Group $group)
    {
        $this->authorizeLeader($group);
        $request->validate([
            'meeting_date' => 'required|date',
            'entry_type' => 'required|in:bulk,individual',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            if ($request->entry_type == 'bulk') {
                $request->validate([
                    'present_count' => 'required|integer|min:0',
                    'absent_count' => 'nullable|integer|min:0',
                    'apology_count' => 'nullable|integer|min:0',
                    'guest_count' => 'nullable|integer|min:0',
                ]);

                GroupMeeting::updateOrCreate(
                    ['group_id' => $group->id, 'meeting_date' => $request->meeting_date],
                    [
                        'present_count' => $request->present_count,
                        'absent_count' => $request->absent_count ?? 0,
                        'apology_count' => $request->apology_count ?? 0,
                        'guest_count' => $request->guest_count ?? 0,
                        'notes' => $request->notes,
                    ]
                );
            } else {
                // Individual marking
                $request->validate([
                    'attendance' => 'required|array',
                    'guest_count' => 'nullable|integer|min:0',
                ]);

                $meeting = GroupMeeting::updateOrCreate(
                    ['group_id' => $group->id, 'meeting_date' => $request->meeting_date],
                    [
                        'notes' => $request->notes,
                        'guest_count' => $request->guest_count ?? 0,
                    ]
                );

                $presentCount = 0;
                $absentCount = 0;
                $apologyCount = 0;

                foreach ($request->attendance as $memberId => $status) {
                    GroupAttendance::updateOrCreate(
                        ['group_meeting_id' => $meeting->id, 'member_id' => $memberId],
                        ['status' => $status]
                    );

                    if ($status == 'present') $presentCount++;
                    elseif ($status == 'absent') $absentCount++;
                    elseif ($status == 'apology') $apologyCount++;
                }

                // Update summary counts
                $meeting->update([
                    'present_count' => $presentCount,
                    'absent_count' => $absentCount,
                    'apology_count' => $apologyCount,
                ]);
            }

            DB::commit();
            return redirect()->route('groups.operations.attendance', $group->id)->with('success', 'Attendance recorded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to record attendance: ' . $e->getMessage());
        }
    }

    public function showMeeting(Group $group, GroupMeeting $meeting)
    {
        $this->authorizeLeader($group);
        $meeting->load('attendances.member');
        return view('groups.operations.meeting_details', compact('group', 'meeting'));
    }

    public function planning(Group $group)
    {
        $this->authorizeLeader($group);
        $plans = $group->plans()->latest()->get();
        return view('groups.operations.planning', compact('group', 'plans'));
    }

    public function storePlan(Request $request, Group $group)
    {
        $this->authorizeLeader($group);
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'budget_amount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $group->plans()->create([
            'title' => $request->title,
            'description' => $request->description,
            'budget_amount' => $request->budget_amount ?? 0,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'draft',
        ]);

        return redirect()->route('groups.operations.planning', $group->id)->with('success', 'Group plan created successfully.');
    }

    public function messages(Group $group)
    {
        $this->authorizeLeader($group);
        $templates = MessageTemplate::where('is_global', true)
            ->orWhere('group_id', $group->id)
            ->where('is_active', true)
            ->get();
            
        $scheduledMessages = $group->scheduledMessages()->latest()->get();
        
        return view('groups.operations.messages', compact('group', 'templates', 'scheduledMessages'));
    }

    public function storeTemplate(Request $request, Group $group)
    {
        $this->authorizeLeader($group);
        $request->validate([
            'name' => 'required|string|max:100',
            'content' => 'required|string|max:160',
        ]);

        $group->messageTemplates()->create([
            'name' => $request->name,
            'content' => $request->content,
            'is_global' => false,
            'is_active' => true,
        ]);

        return back()->with('success', 'Template created successfully.');
    }

    public function scheduleMessage(Request $request, Group $group)
    {
        $this->authorizeLeader($group);
        $request->validate([
            'title' => 'required|string|max:100',
            'message' => 'required|string|max:160',
            'scheduled_at' => 'required|date|after:now',
            'frequency' => 'required|in:once,weekly,monthly',
        ]);

        $group->scheduledMessages()->create($request->all());

        return back()->with('success', 'Message scheduled successfully.');
    }

    public function sendMessage(Request $request, Group $group)
    {
        $this->authorizeLeader($group);
        $request->validate([
            'message' => 'required|string|max:160',
        ]);

        $phones = $group->members()->whereNotNull('phone')->pluck('phone')->toArray();
        if (empty($phones)) {
            return back()->with('error', 'No members with phone numbers found.');
        }

        $result = $this->messagingService->sendSms($phones, $request->message);

        if ($result['status'] == 'success') {
            return back()->with('success', 'Messages sent successfully to ' . count($phones) . ' members.');
        }

        return back()->with('error', 'Failed to send messages: ' . $result['message']);
    }
}
