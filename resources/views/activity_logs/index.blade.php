@extends('layouts.app')

@section('title', 'Activity Logs - TmcsSmart')
@section('page-title', 'System Activity Logs')
@section('breadcrumb', 'TmcsSmart / Administration / Activity Logs')

@section('content')
<div class="animate-in space-y-6">
    <!-- FILTERS -->
    <div class="card p-6 border-none shadow-sm">
        <form action="{{ route('activity-logs.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div class="form-group">
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Search Action</label>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search logs...">
            </div>
            <div class="form-group">
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Module</label>
                <select name="module" class="form-control">
                    <option value="">All Modules</option>
                    @foreach(['Members', 'Finance', 'Groups', 'Events', 'Assets', 'Users', 'Settings'] as $mod)
                        <option value="{{ $mod }}" {{ request('module') == $mod ? 'selected' : '' }}>{{ $mod }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Date</label>
                <input type="date" name="date" value="{{ request('date') }}" class="form-control">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary flex-1">Filter Logs</button>
                <a href="{{ route('activity-logs.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>

    <!-- LOGS TABLE -->
    <div class="card border-none shadow-sm overflow-hidden">
        <div class="table-wrap">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">User</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Action</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Module</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Description</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Date & Time</th>
                        <th class="px-6 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($logs as $log)
                    <tr class="hover:bg-gray-50/50 transition-all">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-green-50 text-green-600 flex-center font-black text-[10px]">
                                    {{ substr($log->user->name, 0, 2) }}
                                </div>
                                <div class="text-xs font-black text-gray-800">{{ $log->user->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="badge {{ str_contains(strtolower($log->action), 'delete') ? 'red' : (str_contains(strtolower($log->action), 'create') ? 'green' : 'blue') }} uppercase text-[9px] font-black">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">{{ $log->module }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs text-gray-600 max-w-xs truncate" title="{{ $log->description }}">
                                {{ $log->description }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-[10px] font-black text-gray-700">{{ $log->created_at->format('M d, Y') }}</div>
                            <div class="text-[9px] text-muted font-bold uppercase">{{ $log->created_at->format('h:i A') }}</div>
                        </td>
                        <td class="px-6 py-4 text-right text-[10px] font-bold text-gray-400">
                            {{ $log->ip_address }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-16 text-center">
                            <div class="w-16 h-16 rounded-full bg-gray-50 text-gray-300 flex-center mx-auto mb-4">
                                <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <p class="text-sm font-black text-gray-400 uppercase tracking-widest">No activity logs found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
        <div class="p-6 border-t border-gray-50">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
