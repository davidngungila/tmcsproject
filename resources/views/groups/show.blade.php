@extends('layouts.app')

@section('title', 'Group Details - TmcsSmart')
@section('page-title', 'Group: ' . $group->name)
@section('breadcrumb', 'TmcsSmart / Groups / ' . $group->name)

@section('content')
<div class="animate-in">
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- LEFT: GROUP INFO -->
    <div class="lg:col-span-1">
      <div class="card mb-6">
        <div class="card-body">
          <div class="flex-center flex-col text-center mb-6">
            <div class="avatar avatar-lg mb-4">{{ substr($group->name, 0, 2) }}</div>
            <h2 class="text-xl font-bold">{{ $group->name }}</h2>
            <span class="badge {{ $group->is_active ? 'green' : 'red' }} mt-2">{{ $group->is_active ? 'Active' : 'Inactive' }}</span>
          </div>

          <div class="space-y-4">
            <div>
              <label class="text-xs text-muted uppercase font-bold">Group Type</label>
              <p class="text-sm">{{ $group->type }}</p>
            </div>
            <div>
              <label class="text-xs text-muted uppercase font-bold">Meeting Schedule</label>
              <p class="text-sm">
                {{ $group->meeting_day ?? 'No set day' }}
                @if($group->meeting_time)
                at {{ \Carbon\Carbon::parse($group->meeting_time)->format('h:i A') }}
                @endif
              </p>
            </div>
            <div>
              <label class="text-xs text-muted uppercase font-bold">Total Members</label>
              <p class="text-sm">{{ $group->members->count() }} members</p>
            </div>
            <div>
              <label class="text-xs text-muted uppercase font-bold">Description</label>
              <p class="text-sm text-muted">{{ $group->description ?? 'No description provided.' }}</p>
            </div>
          </div>

          <div class="mt-8">
            <a href="{{ route('groups.edit', $group->id) }}" class="btn btn-secondary w-full">Edit Group</a>
          </div>
        </div>
      </div>
    </div>

    <!-- RIGHT: MEMBERS LIST -->
    <div class="lg:col-span-2">
      <div class="card">
        <div class="card-header flex items-center justify-between">
          <h3 class="card-title">Members List</h3>
          <span class="text-xs text-muted">{{ $group->members->count() }} total</span>
        </div>
        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th>Member Name</th>
                <th>Reg. Number</th>
                <th>Join Date</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @forelse($group->members as $member)
              <tr>
                <td>
                  <a href="{{ route('members.show', $member->id) }}" class="flex items-center gap-2">
                    <div class="avatar avatar-sm">{{ substr($member->full_name, 0, 2) }}</div>
                    <span style="font-weight:600;">{{ $member->full_name }}</span>
                  </a>
                </td>
                <td>{{ $member->registration_number }}</td>
                <td>{{ $member->pivot->join_date ? \Carbon\Carbon::parse($member->pivot->join_date)->format('M d, Y') : 'N/A' }}</td>
                <td>
                  <span class="badge {{ $member->pivot->is_active ? 'green' : 'red' }}">
                    {{ $member->pivot->is_active ? 'Active' : 'Inactive' }}
                  </span>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="4" class="text-center py-8 text-muted">No members in this group yet.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
