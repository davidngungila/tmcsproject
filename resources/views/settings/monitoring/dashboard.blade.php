@extends('layouts.app')

@section('title', 'Activity Monitoring - TmcsSmart')
@section('page-title', 'Activity Monitoring Dashboard')
@section('breadcrumb', 'TmcsSmart / Settings / Monitoring')

@section('content')
<div class="animate-in space-y-6">
  <!-- SUMMARY STATS -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="card p-5 border-l-4 border-green-500 bg-white shadow-sm">
      <p class="text-[10px] font-black uppercase tracking-widest text-muted mb-1">Live Active Users</p>
      <h3 class="text-xl font-bold text-primary">{{ $activeUsersCount }}</h3>
    </div>
    <div class="card p-5 border-l-4 border-blue-500 bg-white shadow-sm">
      <p class="text-[10px] font-black uppercase tracking-widest text-muted mb-1">Recent Logins (1h)</p>
      <h3 class="text-xl font-bold text-primary">{{ $recentLogins->count() }}</h3>
    </div>
    <div class="card p-5 border-l-4 border-red-500 bg-white shadow-sm">
      <p class="text-[10px] font-black uppercase tracking-widest text-muted mb-1">Failed Attempts</p>
      <h3 class="text-xl font-bold text-primary">{{ $failedLogins->count() }}</h3>
    </div>
    <div class="card p-5 border-l-4 border-amber-500 bg-white shadow-sm">
      <p class="text-[10px] font-black uppercase tracking-widest text-muted mb-1">Suspicious Alerts</p>
      <h3 class="text-xl font-bold text-primary">{{ $suspiciousLogins->count() }}</h3>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- RECENT ACTIONS -->
    <div class="card bg-white shadow-sm overflow-hidden">
      <div class="p-6 border-b flex items-center justify-between">
        <h3 class="font-bold text-lg">Recent Actions Feed</h3>
        <a href="{{ route('settings.monitoring.action-logs') }}" class="text-xs text-blue-600 hover:underline">View All</a>
      </div>
      <div class="p-0">
        <div class="divide-y divide-muted/10 max-h-96 overflow-y-auto">
          @foreach($recentActions as $action)
          <div class="p-4 flex gap-3 hover:bg-muted/5 transition-colors">
            <div class="w-8 h-8 rounded-full bg-muted/20 flex items-center justify-center flex-shrink-0 text-xs font-bold uppercase">
              {{ substr($action->user->name ?? 'S', 0, 1) }}
            </div>
            <div>
              <div class="text-xs font-bold">{{ $action->user->name ?? 'System' }}</div>
              <div class="text-[11px] text-primary">{{ $action->description }}</div>
              <div class="flex items-center gap-2 mt-1">
                <span class="text-[10px] text-muted">{{ $action->created_at->diffForHumans() }}</span>
                <span class="text-[10px] px-1.5 py-0.5 rounded bg-muted/10 text-muted uppercase font-bold">{{ $action->module }}</span>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>

    <!-- RECENT LOGINS -->
    <div class="card bg-white shadow-sm overflow-hidden">
      <div class="p-6 border-b flex items-center justify-between">
        <h3 class="font-bold text-lg">Login Activity</h3>
        <a href="{{ route('settings.monitoring.auth-logs') }}" class="text-xs text-blue-600 hover:underline">View All</a>
      </div>
      <div class="p-0">
        <div class="divide-y divide-muted/10 max-h-96 overflow-y-auto">
          @foreach($recentLogins as $login)
          <div class="p-4 flex items-center justify-between hover:bg-muted/5 transition-colors">
            <div class="flex items-center gap-3">
              <div class="w-2 h-2 rounded-full bg-green-500"></div>
              <div>
                <div class="text-xs font-bold">{{ $login->user->name }}</div>
                <div class="text-[10px] text-muted">{{ $login->ip_address }} • {{ $login->browser }} on {{ $login->device }}</div>
              </div>
            </div>
            <div class="text-[10px] text-muted">{{ $login->login_at->diffForHumans() }}</div>
          </div>
          @endforeach
          
          @foreach($failedLogins as $failed)
          <div class="p-4 flex items-center justify-between bg-red-500/5 hover:bg-red-500/10 transition-colors">
            <div class="flex items-center gap-3">
              <div class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></div>
              <div>
                <div class="text-xs font-bold text-red-600">Failed Attempt: {{ $failed->email }}</div>
                <div class="text-[10px] text-muted">{{ $failed->ip_address }} • Reason: {{ $failed->failure_reason }}</div>
              </div>
            </div>
            <div class="text-[10px] text-muted">{{ $failed->created_at->diffForHumans() }}</div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
