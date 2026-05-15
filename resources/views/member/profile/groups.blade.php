@extends('layouts.app')

@section('title', 'My Groups - TMCS Smart')
@section('page-title', 'My Groups')
@section('breadcrumb', 'Home / Member / Groups')

@section('content')
<div class="animate-in space-y-8">

  @if(isset($ledGroups) && $ledGroups->count() > 0)
  <!-- LEADERSHIP SECTION -->
  <div class="card border-l-4 border-amber-500 shadow-xl">
    <div class="card-header bg-amber-50/30 border-b p-6 flex items-center justify-between">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-amber-500 text-white flex-center shadow-lg">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
        </div>
        <div>
          <h3 class="text-lg font-black text-amber-900 uppercase tracking-wider">Groups I Lead</h3>
          <p class="text-xs text-amber-600 font-bold uppercase tracking-widest">Leadership Management Tools</p>
        </div>
      </div>
      <span class="badge gold uppercase font-black text-[10px] px-3 py-1">Administrative Access</span>
    </div>
    <div class="card-body p-0">
      <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-amber-100">
        @foreach($ledGroups as $group)
        <div class="p-8 hover:bg-amber-50/10 transition-all">
          <div class="flex items-start justify-between mb-6">
            <div>
              <h4 class="text-xl font-black text-gray-800">{{ $group->name }}</h4>
              <div class="flex items-center gap-2 mt-1">
                <span class="text-[10px] font-black uppercase tracking-widest text-amber-600">My Role:</span>
                <span class="badge gold text-[9px] uppercase font-bold">
                    @if($group->chairperson_id == $member->id) Chairperson @endif
                    @if($group->secretary_id == $member->id) Secretary @endif
                    @if($group->accountant_id == $member->id) Accountant @endif
                </span>
              </div>
            </div>
            <div class="text-right">
              <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $group->type }}</div>
              <div class="text-xs font-black text-amber-600">{{ $group->meeting_day ?: 'No Meeting Day' }}</div>
            </div>
          </div>

          <!-- QUICK ACTIONS -->
          <div class="grid grid-cols-2 gap-3 mb-6">
            <a href="{{ route('groups.operations.members', $group->id) }}" class="flex flex-col items-center justify-center p-4 rounded-2xl bg-white border border-amber-100 hover:border-amber-600 hover:shadow-md transition-all group">
                <svg width="20" height="20" class="text-amber-400 group-hover:text-amber-600 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span class="text-[10px] font-black uppercase tracking-widest text-gray-500 group-hover:text-amber-600">Members</span>
            </a>
            <a href="{{ route('groups.operations.contributions', $group->id) }}" class="flex flex-col items-center justify-center p-4 rounded-2xl bg-white border border-amber-100 hover:border-green-600 hover:shadow-md transition-all group">
                <svg width="20" height="20" class="text-amber-400 group-hover:text-green-600 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-[10px] font-black uppercase tracking-widest text-gray-500 group-hover:text-green-600">Giving</span>
            </a>
            <a href="{{ route('groups.operations.attendance', $group->id) }}" class="flex flex-col items-center justify-center p-4 rounded-2xl bg-white border border-amber-100 hover:border-indigo-600 hover:shadow-md transition-all group">
                <svg width="20" height="20" class="text-amber-400 group-hover:text-indigo-600 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                <span class="text-[10px] font-black uppercase tracking-widest text-gray-500 group-hover:text-indigo-600">Attendance</span>
            </a>
            <a href="{{ route('groups.operations.messages', $group->id) }}" class="flex flex-col items-center justify-center p-4 rounded-2xl bg-white border border-amber-100 hover:border-purple-600 hover:shadow-md transition-all group">
                <svg width="20" height="20" class="text-amber-400 group-hover:text-purple-600 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <span class="text-[10px] font-black uppercase tracking-widest text-gray-500 group-hover:text-purple-600">Message</span>
            </a>
          </div>

          <a href="{{ route('groups.operations.planning', $group->id) }}" class="btn btn-warning w-full py-4 font-black uppercase tracking-[0.2em] text-[10px] text-amber-900">
            Strategic Planning & Budgeting
          </a>
        </div>
        @endforeach
      </div>
    </div>
  </div>
  @endif

  <!-- MEMBERSHIP SECTION -->
  <div class="card shadow-sm border-none">
    <div class="card-header border-b p-6">
      <h3 class="text-sm font-black text-gray-800 uppercase tracking-widest">My Group Memberships</h3>
      <p class="text-xs text-muted font-medium">Ministries and associations you are part of</p>
    </div>
    <div class="card-body p-8">
      @if($groups->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          @foreach($groups as $group)
            <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100 flex items-start gap-5 hover:shadow-md transition-shadow group">
              <div class="w-14 h-14 rounded-2xl bg-white flex-center shadow-sm text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition-all">
                <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
              </div>
              <div>
                <div class="font-black text-gray-800 mb-1">{{ $group->name }}</div>
                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">{{ $group->type }} • Joined: {{ $group->pivot->join_date ? \Carbon\Carbon::parse($group->pivot->join_date)->format('M d, Y') : 'N/A' }}</div>
                <span class="badge gold text-[9px] uppercase font-bold">Active Member</span>
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div class="text-center py-20 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
          <div class="w-20 h-20 rounded-full bg-white shadow-sm flex-center mx-auto mb-6">
            <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" class="text-gray-300"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
          </div>
          <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">No Group Memberships Found</p>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
