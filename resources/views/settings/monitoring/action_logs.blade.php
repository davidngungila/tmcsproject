@extends('layouts.app')

@section('title', 'Action Logs - TmcsSmart')
@section('page-title', 'System Activity Logs')
@section('breadcrumb', 'TmcsSmart / Settings / Monitoring / Action Logs')

@section('content')
<div class="animate-in space-y-6">
  <div class="card bg-white shadow-sm overflow-hidden">
    <div class="p-6 border-b flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h3 class="font-bold text-lg">System Actions History</h3>
        <p class="text-xs text-muted">Detailed log of all administrative and system-wide changes.</p>
      </div>
      <form action="{{ route('settings.monitoring.action-logs') }}" method="GET" class="flex flex-wrap gap-2">
        <select name="user_id" class="form-control text-xs w-40">
          <option value="">All Users</option>
          @foreach($users as $user)
            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
          @endforeach
        </select>
        <select name="module" class="form-control text-xs w-32">
          <option value="">All Modules</option>
          @foreach($modules as $module)
            <option value="{{ $module }}" {{ request('module') == $module ? 'selected' : '' }}>{{ ucfirst($module) }}</option>
          @endforeach
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
      </form>
    </div>

    <div class="table-wrap">
      <table class="w-full">
        <thead>
          <tr class="bg-muted/5 text-[10px] font-black uppercase tracking-widest text-muted">
            <th class="px-6 py-4 text-left">User</th>
            <th class="px-6 py-4 text-left">Module</th>
            <th class="px-6 py-4 text-left">Action</th>
            <th class="px-6 py-4 text-left">IP Address</th>
            <th class="px-6 py-4 text-left">Time</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-muted/10">
          @forelse($logs as $log)
          <tr class="hover:bg-primary/5 transition-colors text-xs">
            <td class="px-6 py-4">
              <div class="font-bold text-primary">{{ $log->user->name ?? 'System' }}</div>
              <div class="text-[10px] text-muted">{{ $log->user->email ?? '' }}</div>
            </td>
            <td class="px-6 py-4">
              <span class="px-2 py-0.5 rounded bg-muted/10 text-muted text-[10px] font-black uppercase tracking-widest">
                {{ $log->module }}
              </span>
            </td>
            <td class="px-6 py-4">
              <div class="font-medium text-primary">{{ $log->description }}</div>
              @if($log->changes)
                <button class="text-[9px] text-blue-600 hover:underline mt-1" onclick="showChanges('{{ $log->id }}')">View Detailed Changes</button>
                <div id="changes-{{ $log->id }}" class="hidden mt-2 p-2 bg-muted/5 rounded border text-[9px] mono whitespace-pre-wrap">{{ json_encode($log->changes, JSON_PRETTY_PRINT) }}</div>
              @endif
            </td>
            <td class="px-6 py-4 mono text-muted">{{ $log->ip_address }}</td>
            <td class="px-6 py-4 text-muted">{{ $log->created_at->format('M d, H:i:s') }}</td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="px-6 py-12 text-center text-muted italic">No activity logs found</td>
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

@push('scripts')
<script>
  function showChanges(id) {
    const el = document.getElementById('changes-' + id);
    el.classList.toggle('hidden');
  }
</script>
@endpush
@endsection
