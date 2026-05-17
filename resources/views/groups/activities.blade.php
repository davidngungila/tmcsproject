@extends('layouts.app')

@section('title', 'Group Activities - TmcsSmart')
@section('page-title', 'Group Activities & Events')
@section('breadcrumb', 'TmcsSmart / Groups / Activities')

@section('content')
<div class="animate-in">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Community Activities</h2>
            <p class="text-sm text-muted mt-1">Real-time log of all group operations and events</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('groups.index') }}" class="btn btn-secondary flex items-center gap-2">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                Manage Groups
            </a>
            <a href="{{ route('groups.create') }}" class="btn btn-primary flex items-center gap-2">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
                New Group
            </a>
        </div>
    </div>

    @php
        $activities = \App\Models\GroupMeeting::with('group')->latest()->paginate(15);
    @endphp

    <div class="card shadow-sm border-none">
        <div class="table-wrap">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="p-4 text-[10px] font-black uppercase tracking-widest text-gray-400">Date</th>
                        <th class="p-4 text-[10px] font-black uppercase tracking-widest text-gray-400">Community</th>
                        <th class="p-4 text-[10px] font-black uppercase tracking-widest text-gray-400">Operation</th>
                        <th class="p-4 text-[10px] font-black uppercase tracking-widest text-gray-400 text-center">Attendance</th>
                        <th class="p-4 text-[10px] font-black uppercase tracking-widest text-gray-400 text-right">Impact</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($activities as $activity)
                    <tr class="hover:bg-light/30 transition-all">
                        <td class="p-4 text-xs font-bold text-gray-600">{{ $activity->meeting_date->format('M d, Y') }}</td>
                        <td class="p-4">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded bg-green-50 text-green-600 flex-center text-[10px] font-black">{{ substr($activity->group->name, 0, 2) }}</div>
                                <span class="text-sm font-bold text-gray-800">{{ $activity->group->name }}</span>
                            </div>
                        </td>
                        <td class="p-4">
                            <span class="badge green uppercase font-black text-[9px]">Meeting Record</span>
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex justify-center gap-1">
                                <span class="badge green text-[9px]">{{ $activity->present_count }} P</span>
                                <span class="badge amber text-[9px]">{{ $activity->guest_count }} G</span>
                            </div>
                        </td>
                        <td class="p-4 text-right font-black text-sm text-green-600">
                            +TZS {{ number_format($activity->total_collected, 0) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-12 text-center text-muted italic text-xs">No recent activities recorded.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-gray-50/30">
            {{ $activities->links() }}
        </div>
    </div>
</div>
@endsection
