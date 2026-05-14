<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{
    public function index()
    {
        $members = Member::with(['groups'])->paginate(10);
        return view('members.index', compact('members'));
    }

    public function create()
    {
        $groups = Group::all();
        return view('members.create', compact('groups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:members,email',
            'phone' => 'nullable|string|max:20',
            'member_type' => 'required|string',
            'category' => 'nullable|string',
            'date_of_birth' => 'required|date',
            'address' => 'required|string',
            'baptismal_name' => 'nullable|string',
            'registration_date' => 'required|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('members', 'public');
            $validated['photo'] = $path;
        }

        $validated['registration_number'] = 'TMCS-' . date('Y') . '-' . str_pad(Member::count() + 1, 3, '0', STR_PAD_LEFT);
        $validated['qr_code'] = 'QR-' . strtoupper(Str::random(10));
        $validated['is_active'] = true;
        $validated['created_by'] = auth()->id();

        $member = Member::create($validated);

        // Auto-create User account for Member if email exists
        if ($member->email) {
            // Get last name and capitalize it for the password
            $nameParts = explode(' ', trim($member->full_name));
            $lastName = end($nameParts);
            $password = strtoupper($lastName);

            $user = User::create([
                'name' => $member->full_name,
                'email' => $member->email,
                'password' => Hash::make($password),
                'phone' => $member->phone,
            ]);

            // Assign 'member' role if it exists
            $memberRole = \App\Models\Role::where('name', 'member')->first();
            if ($memberRole) {
                $user->roles()->attach($memberRole->id);
            }

            // Link member to user
            $member->update(['user_id' => $user->id]);
        }

        if ($request->has('groups')) {
            $member->groups()->attach($request->groups, ['join_date' => now(), 'is_active' => true]);
        }

        return redirect()->route('members.index')->with('success', 'Member registered successfully. User account created (Username: ' . $member->email . ')');
    }

    public function show(Member $member)
    {
        $member->load(['financials', 'groups', 'contributions']);
        return view('members.show', compact('member'));
    }

    public function edit(Member $member)
    {
        $groups = Group::all();
        $memberGroups = $member->groups->pluck('id')->toArray();
        return view('members.edit', compact('member', 'groups', 'memberGroups'));
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:members,email,' . $member->id,
            'phone' => 'nullable|string|max:20',
            'member_type' => 'required|string',
            'category' => 'nullable|string',
            'date_of_birth' => 'required|date',
            'address' => 'required|string',
            'baptismal_name' => 'nullable|string',
            'is_active' => 'required|boolean',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($member->photo) {
                Storage::disk('public')->delete($member->photo);
            }
            
            $path = $request->file('photo')->store('members', 'public');
            $validated['photo'] = $path;
        }

        $member->update($validated);

        if ($request->has('groups')) {
            $syncData = [];
            foreach ($request->groups as $groupId) {
                $syncData[$groupId] = [
                    'join_date' => now(),
                    'is_active' => true
                ];
            }
            $member->groups()->sync($syncData);
        }

        return redirect()->route('members.index')->with('success', 'Member updated successfully');
    }

    public function destroy(Member $member)
    {
        $member->delete();
        return redirect()->route('members.index')->with('success', 'Member deleted successfully');
    }

    public function idCard(Member $member)
    {
        $member->load('groups');
        return view('members.id_card', compact('member'));
    }

    public function categories()
    {
        // For now, static categories. In a full system, these might be in a separate table.
        $categories = [
            'Undergraduate', 'Postgraduate', 'Teaching Staff', 'Non-Teaching Staff', 
            'Sunday School', 'Community Member', 'Elder'
        ];
        return view('members.categories', compact('categories'));
    }
}
