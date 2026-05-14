@extends('layouts.app')

@section('title', 'My Profile - TmcsSmart')
@section('page-title', 'My Profile')
@section('breadcrumb', 'TmcsSmart / Member / Profile')

@section('content')
<div class="animate-in">
  <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    
    <!-- LEFT COLUMN: PHOTO & QUICK INFO -->
    <div class="lg:col-span-1 space-y-6">
      <div class="card text-center">
        <div class="card-body py-8">
          <div class="w-32 h-32 rounded-full border-4 border-light mx-auto mb-4 overflow-hidden bg-light flex-center">
            @if($member->photo)
              <img src="{{ asset('storage/' . $member->photo) }}" class="w-full h-full object-cover">
            @else
              <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" class="text-muted"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            @endif
          </div>
          <h3 class="font-bold text-lg mb-1">{{ $member->full_name }}</h3>
          <p class="text-xs text-muted mb-4">{{ $member->registration_number }}</p>
          <div class="badge {{ $member->is_active ? 'green' : 'red' }} mb-6">
            {{ $member->is_active ? 'Active Member' : 'Inactive' }}
          </div>
          <div class="flex flex-col gap-2">
            <a href="{{ route('member.profile.pay') }}" class="btn btn-primary w-full btn-sm">
              <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="mr-1.5"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              Make Payment
            </a>
            <a href="{{ route('member.profile.edit') }}" class="btn btn-secondary w-full btn-sm">Edit Profile</a>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header border-b">
          <div class="card-title text-sm">Quick Stats</div>
        </div>
        <div class="card-body p-0">
          <div class="divide-y divide-gray-50">
            <div class="p-4 flex justify-between items-center">
              <span class="text-xs text-muted uppercase font-bold tracking-wider">Type</span>
              <span class="text-sm font-medium capitalize">{{ $member->member_type }}</span>
            </div>
            <div class="p-4 flex justify-between items-center">
              <span class="text-xs text-muted uppercase font-bold tracking-wider">Join Date</span>
              <span class="text-sm font-medium">{{ $member->registration_date->format('M Y') }}</span>
            </div>
            <div class="p-4 flex justify-between items-center">
              <span class="text-xs text-muted uppercase font-bold tracking-wider">Total Contributions</span>
              <span class="text-sm font-medium">{{ number_format($member->contributions->sum('amount')) }} TZS</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- RIGHT COLUMN: DETAILS, GROUPS, CONTRIBUTIONS -->
    <div class="lg:col-span-3 space-y-6">
      
      <!-- PERSONAL DETAILS -->
      <div class="card">
        <div class="card-header border-b">
          <div class="card-title">Personal Details</div>
        </div>
        <div class="card-body">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
            <div>
              <label class="text-xs text-muted uppercase font-bold tracking-wider mb-1 block">Email Address</label>
              <div class="text-sm font-medium">{{ $member->email ?? 'Not provided' }}</div>
            </div>
            <div>
              <label class="text-xs text-muted uppercase font-bold tracking-wider mb-1 block">Phone Number</label>
              <div class="text-sm font-medium">{{ $member->phone ?? 'Not provided' }}</div>
            </div>
            <div>
              <label class="text-xs text-muted uppercase font-bold tracking-wider mb-1 block">Date of Birth</label>
              <div class="text-sm font-medium">{{ $member->date_of_birth->format('d M, Y') }}</div>
            </div>
            <div>
              <label class="text-xs text-muted uppercase font-bold tracking-wider mb-1 block">Baptismal Name</label>
              <div class="text-sm font-medium">{{ $member->baptismal_name ?? 'N/A' }}</div>
            </div>
            <div class="md:col-span-2">
              <label class="text-xs text-muted uppercase font-bold tracking-wider mb-1 block">Residential Address</label>
              <div class="text-sm font-medium">{{ $member->address }}</div>
            </div>
          </div>
        </div>
      </div>

      <!-- COMMUNITIES & GROUPS -->
      <div class="card">
        <div class="card-header border-b">
          <div class="card-title">My Communities & Groups</div>
        </div>
        <div class="card-body">
          @if($member->groups->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              @foreach($member->groups as $group)
                <div class="p-4 bg-light rounded-xl border border-gray-100 flex items-center gap-4">
                  <div class="w-12 h-12 rounded-lg bg-white flex-center shadow-sm text-{{ $group->type === 'Community' ? 'blue' : 'green' }}-500">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                  </div>
                  <div>
                    <div class="font-bold text-sm">{{ $group->name }}</div>
                    <div class="text-[11px] text-muted">{{ $group->type }} • Joined {{ $group->pivot->join_date ? \Carbon\Carbon::parse($group->pivot->join_date)->format('M Y') : 'N/A' }}</div>
                  </div>
                </div>
              @endforeach
            </div>
          @else
            <div class="text-center py-8 text-muted">
              <p class="text-sm">You haven't joined any groups yet.</p>
            </div>
          @endif
        </div>
      </div>

      <!-- RECENT CONTRIBUTIONS -->
      <div class="card">
        <div class="card-header border-b flex items-center justify-between">
          <div class="card-title">Recent Contributions</div>
          <a href="#" class="text-xs text-blue-500 font-bold hover:underline">View All</a>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead class="bg-light/50 border-b">
              <tr>
                <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-muted">Date</th>
                <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-muted">Type</th>
                <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-muted">Method</th>
                <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-muted text-right">Amount</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              @forelse($member->contributions as $contribution)
                <tr class="hover:bg-light/30 transition-colors">
                  <td class="px-6 py-4 text-sm">{{ $contribution->contribution_date->format('d M, Y') }}</td>
                  <td class="px-6 py-4 text-sm font-medium">{{ $contribution->contribution_type }}</td>
                  <td class="px-6 py-4 text-sm capitalize text-muted">{{ str_replace('_', ' ', $contribution->payment_method) }}</td>
                  <td class="px-6 py-4 text-sm font-bold text-right">{{ number_format($contribution->amount) }} TZS</td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="px-6 py-8 text-center text-muted text-sm">No contributions recorded yet.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <!-- RECENT ATTENDANCE -->
      <div class="card">
        <div class="card-header border-b">
          <div class="card-title">Recent Event Attendance</div>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead class="bg-light/50 border-b">
              <tr>
                <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-muted">Event</th>
                <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-muted">Date</th>
                <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-muted">Status</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              @forelse($member->eventAttendance->take(5) as $attendance)
                <tr class="hover:bg-light/30 transition-colors">
                  <td class="px-6 py-4 text-sm font-medium">{{ $attendance->event->event_name }}</td>
                  <td class="px-6 py-4 text-sm text-muted">{{ $attendance->event->event_date->format('d M, Y') }}</td>
                  <td class="px-6 py-4 text-sm">
                    <span class="badge {{ $attendance->status === 'attended' ? 'green' : 'amber' }}">
                      {{ ucfirst($attendance->status) }}
                    </span>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="3" class="px-6 py-8 text-center text-muted text-sm">No attendance records found.</td>
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
