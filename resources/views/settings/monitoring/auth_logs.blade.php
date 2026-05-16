@extends('layouts.app')

@section('title', 'Authentication Logs - TmcsSmart')
@section('page-title', 'Security Logs')
@section('breadcrumb', 'TmcsSmart / Settings / Monitoring / Auth Logs')

@section('content')
<div class="animate-in space-y-6">
  <div class="card bg-white shadow-sm overflow-hidden">
    <div class="p-6 border-b flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h3 class="font-bold text-lg">Authentication History</h3>
        <p class="text-xs text-muted">Track login attempts, failures, and device information.</p>
      </div>
      <form action="{{ route('settings.monitoring.auth-logs') }}" method="GET" class="flex flex-wrap gap-2">
        <select name="user_id" class="form-control text-xs w-40">
          <option value="">All Users</option>
          @foreach($users as $user)
            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
          @endforeach
        </select>
        <select name="status" class="form-control text-xs w-32">
          <option value="">All Status</option>
          <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success</option>
          <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
      </form>
    </div>

    <div class="table-wrap">
      <table class="w-full">
        <thead>
          <tr class="bg-muted/5 text-[10px] font-black uppercase tracking-widest text-muted">
            <th class="px-6 py-4 text-left">User / Email</th>
            <th class="px-6 py-4 text-left">Status</th>
            <th class="px-6 py-4 text-left">IP Address</th>
            <th class="px-6 py-4 text-left">Device / Browser</th>
            <th class="px-6 py-4 text-left">Time</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-muted/10">
          @forelse($logs as $log)
          <tr class="hover:bg-primary/5 transition-colors text-xs">
            <td class="px-6 py-4">
              @if($log->user)
                <div class="font-bold">{{ $log->user->name }}</div>
                <div class="text-[10px] text-muted">{{ $log->user->email }}</div>
              @else
                <div class="font-bold text-muted italic">{{ $log->email ?: 'Unknown' }}</div>
              @endif
            </td>
            <td class="px-6 py-4">
              <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full {{ $log->login_successful ? 'bg-green-500/10 text-green-600' : 'bg-red-500/10 text-red-600' }} text-[10px] font-black uppercase tracking-wider">
                <span class="w-1.5 h-1.5 rounded-full {{ $log->login_successful ? 'bg-green-500' : 'bg-red-500' }}"></span>
                {{ $log->login_successful ? 'Success' : 'Failed' }}
              </span>
              @if(!$log->login_successful && $log->failure_reason)
                <div class="text-[9px] text-red-500 mt-1">{{ $log->failure_reason }}</div>
              @endif
            </td>
            <td class="px-6 py-4 mono text-muted">{{ $log->ip_address }}</td>
            <td class="px-6 py-4">
              <div class="text-primary">{{ $log->device }}</div>
              <div class="text-[10px] text-muted">{{ $log->browser }}</div>
            </td>
            <td class="px-6 py-4 text-muted">{{ $log->created_at->format('M d, H:i:s') }}</td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="px-6 py-12 text-center text-muted italic">No authentication logs found</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="px-6 py-4 border-t">
      {{ $logs->links() }}
    </div>
  </div>
</div>
@endsection
