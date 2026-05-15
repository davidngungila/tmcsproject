@extends('layouts.app')

@section('title', 'Small Communities - TmcsSmart')
@section('page-title', 'Small Christian Communities (SCC)')
@section('breadcrumb', 'TmcsSmart / Groups / Communities')

@section('content')
<div class="animate-in">
  <div class="flex items-center justify-between mb-6">
    <div>
      <h2 class="text-lg font-bold">Communities</h2>
      <p class="text-sm text-muted">Manage small community groups and assignments</p>
    </div>
    <a href="{{ route('groups.create') }}?type=Community" class="btn btn-primary">Add New Community</a>
  </div>

  <!-- COMMUNITY STATISTICS -->
  <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
    <div class="card p-6 border-none shadow-sm bg-gradient-to-br from-green-600 to-green-700 text-white">
      <div class="text-[10px] font-black uppercase tracking-widest opacity-80">Total Communities</div>
      <div class="text-2xl font-black mt-2">{{ $totalCommunities }}</div>
      <div class="text-[10px] font-bold mt-4">Active: {{ $activeCommunities }}</div>
    </div>
    <div class="card p-6 border-none shadow-sm">
      <div class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Total Membership</div>
      <div class="text-2xl font-black text-gray-800 mt-2">{{ number_format($totalCommunityMembers) }}</div>
      <div class="text-[10px] font-bold text-green-600 mt-4 uppercase tracking-widest">Growing Strong</div>
    </div>
    <div class="card p-6 border-none shadow-sm">
      <div class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Total Collections</div>
      <div class="text-2xl font-black text-gray-800 mt-2">TZS {{ number_format($communityCollections, 0) }}</div>
      <div class="text-[10px] font-bold text-amber-600 mt-4 uppercase tracking-widest">Community Giving</div>
    </div>
    <div class="card p-6 border-none shadow-sm">
      <div class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Avg Size</div>
      <div class="text-2xl font-black text-gray-800 mt-2">{{ $totalCommunities > 0 ? round($totalCommunityMembers / $totalCommunities, 1) : 0 }}</div>
      <div class="text-[10px] font-bold text-blue-600 mt-4 uppercase tracking-widest">Members / Community</div>
    </div>
  </div>

  <div class="card overflow-hidden">
    <table class="w-full text-left">
      <thead>
        <tr class="bg-light/50 border-b border-light">
          <th class="p-4 text-xs font-black uppercase tracking-widest text-muted">Community</th>
          <th class="p-4 text-xs font-black uppercase tracking-widest text-muted">Leadership</th>
          <th class="p-4 text-xs font-black uppercase tracking-widest text-muted text-center">Size</th>
          <th class="p-4 text-xs font-black uppercase tracking-widest text-muted text-right">Operations</th>
        </tr>
      </thead>
      <tbody>
        @forelse($groups as $group)
        <tr class="border-b border-light hover:bg-light/30 transition-all">
          <td class="p-4">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-lg bg-green-50 text-green-600 flex items-center justify-center font-bold">
                {{ substr($group->name, 0, 2) }}
              </div>
              <div>
                <div class="font-bold text-sm text-gray-800">{{ $group->name }}</div>
                <div class="text-[9px] text-muted font-black uppercase tracking-widest">{{ $group->meeting_day ?: 'No Fixed Day' }}</div>
              </div>
            </div>
          </td>
          <td class="p-4">
            <div class="flex flex-col gap-1">
              <div class="text-[10px] font-bold text-gray-700"><span class="text-gray-400 font-black uppercase text-[8px] mr-1">C:</span>{{ $group->chairperson->full_name ?? 'Not Assigned' }}</div>
              <div class="text-[10px] font-bold text-gray-700"><span class="text-gray-400 font-black uppercase text-[8px] mr-1">S:</span>{{ $group->secretary->full_name ?? 'Not Assigned' }}</div>
            </div>
          </td>
          <td class="p-4 text-center">
            <span class="text-sm font-black text-gray-800">{{ $group->members_count }}</span>
          </td>
          <td class="p-4">
            <div class="flex items-center justify-end gap-1">
              <a href="{{ route('groups.operations.members', $group->id) }}" class="btn btn-ghost btn-sm text-green-600 p-1.5" title="Community Stats">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              </a>
              <a href="{{ route('groups.operations.contributions', $group->id) }}" class="btn btn-ghost btn-sm text-green-600 p-1.5" title="Giving Record">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              </a>
              <a href="{{ route('groups.operations.attendance', $group->id) }}" class="btn btn-ghost btn-sm text-green-600 p-1.5" title="Attendance">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
              </a>
              <a href="{{ route('groups.show', $group->id) }}" class="btn btn-ghost btn-sm text-gray-400 p-1.5" title="All Operations">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              </a>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="4" class="p-12 text-center text-muted">No communities found.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-6">
    {{ $groups->links() }}
  </div>
</div>
@endsection
