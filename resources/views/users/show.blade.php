@extends('layouts.app')

@section('title', 'User Profile: ' . $user->name . ' - TmcsSmart')
@section('page-title', 'User Profile')
@section('breadcrumb', 'TmcsSmart / Users / ' . $user->name)

@section('content')
<div class="animate-in space-y-6">
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- USER CARD -->
    <div class="lg:col-span-1 space-y-6">
      <div class="card bg-white shadow-sm overflow-hidden">
        <div class="h-24 bg-gradient-to-r from-green-600 to-green-400"></div>
        <div class="px-6 pb-6 -mt-12 text-center">
          <div class="inline-block p-1 bg-white rounded-full mb-4">
            <div class="w-24 h-24 rounded-full bg-green-500/10 text-green-600 flex items-center justify-center overflow-hidden text-2xl font-bold border-4 border-white shadow-sm">
              @if($user->profile_image)
                <img src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
              @else
                {{ substr($user->name, 0, 1) }}
              @endif
            </div>
          </div>
          <h3 class="text-xl font-bold text-primary">{{ $user->name }}</h3>
          <p class="text-sm text-muted mb-4">{{ $user->email }}</p>
          
          <div class="flex flex-wrap justify-center gap-2 mb-6">
            @foreach($user->roles as $role)
            <span class="px-3 py-1 rounded-full bg-blue-500/10 text-blue-600 text-[10px] font-black uppercase tracking-widest">
              {{ $role->name }}
            </span>
            @endforeach
            <span class="px-3 py-1 rounded-full {{ $user->is_active ? 'bg-green-500/10 text-green-600' : 'bg-red-500/10 text-red-600' }} text-[10px] font-black uppercase tracking-widest">
              {{ $user->is_active ? 'Active' : 'Inactive' }}
            </span>
          </div>

          <div class="grid grid-cols-2 gap-4 pt-6 border-t border-dashed">
            <div>
              <p class="text-[10px] font-black uppercase tracking-widest text-muted mb-1">Joined</p>
              <p class="text-xs font-bold">{{ $user->created_at->format('M Y') }}</p>
            </div>
            <div>
              <p class="text-[10px] font-black uppercase tracking-widest text-muted mb-1">Last Login</p>
              <p class="text-xs font-bold">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- CONTACT INFO -->
      <div class="card bg-white shadow-sm p-6">
        <h4 class="text-xs font-black uppercase tracking-widest text-primary border-b pb-3 mb-4">Contact Information</h4>
        <div class="space-y-4">
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded bg-muted/5 flex items-center justify-center text-muted">
              <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <div>
              <p class="text-[10px] text-muted uppercase font-bold">Email Address</p>
              <p class="text-sm font-medium">{{ $user->email }}</p>
            </div>
          </div>
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded bg-muted/5 flex items-center justify-center text-muted">
              <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 011.94.76l-1.5 6.74a1 1 0 01-1.7.54l-2.24-2.24a15.58 15.58 0 006.76 6.76l2.24-2.24a1 1 0 011.06-.24l6.74 1.5a1 1 0 01.76 1.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
            </div>
            <div>
              <p class="text-[10px] text-muted uppercase font-bold">Phone Number</p>
              <p class="text-sm font-medium">{{ $user->phone ?: 'Not provided' }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ACTIVITY & LOGS -->
    <div class="lg:col-span-2 space-y-6">
      <!-- LOGIN HISTORY -->
      <div class="card bg-white shadow-sm overflow-hidden">
        <div class="p-6 border-b bg-muted/5 flex items-center justify-between">
          <h3 class="font-bold text-lg">Login History</h3>
          <span class="text-[10px] font-bold uppercase tracking-widest text-muted">Recent Sessions</span>
        </div>
        <div class="table-wrap">
          <table class="w-full">
            <thead>
              <tr class="bg-muted/5 text-[10px] font-black uppercase tracking-widest text-muted">
                <th class="px-6 py-4 text-left">Time</th>
                <th class="px-6 py-4 text-left">IP Address</th>
                <th class="px-6 py-4 text-left">Status</th>
                <th class="px-6 py-4 text-left">Device / Browser</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-muted/10">
              @forelse($user->authenticationLogs()->latest()->take(5)->get() as $log)
              <tr class="text-xs">
                <td class="px-6 py-4 font-medium">{{ $log->created_at->format('M d, H:i') }}</td>
                <td class="px-6 py-4 mono">{{ $log->ip_address }}</td>
                <td class="px-6 py-4">
                  <span class="px-2 py-0.5 rounded-full {{ $log->login_successful ? 'bg-green-500/10 text-green-600' : 'bg-red-500/10 text-red-600' }} text-[9px] font-black uppercase">
                    {{ $log->login_successful ? 'Success' : 'Failed' }}
                  </span>
                </td>
                <td class="px-6 py-4 text-muted">{{ $log->browser }} on {{ $log->device }}</td>
              </tr>
              @empty
              <tr>
                <td colspan="4" class="px-6 py-12 text-center text-muted italic">No login records found</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <!-- RECENT ACTIONS -->
      <div class="card bg-white shadow-sm overflow-hidden">
        <div class="p-6 border-b bg-muted/5 flex items-center justify-between">
          <h3 class="font-bold text-lg">System Activity</h3>
          <span class="text-[10px] font-bold uppercase tracking-widest text-muted">Actions performed</span>
        </div>
        <div class="p-0">
          <div class="divide-y divide-muted/10">
            @forelse($user->activityLogs()->latest()->take(10)->get() as $activity)
            <div class="p-4 hover:bg-muted/5 transition-colors">
              <div class="flex items-center justify-between mb-1">
                <span class="text-xs font-bold text-primary">{{ $activity->description }}</span>
                <span class="text-[10px] text-muted">{{ $activity->created_at->diffForHumans() }}</span>
              </div>
              <div class="flex items-center gap-2">
                <span class="text-[9px] font-black uppercase tracking-widest px-1.5 py-0.5 rounded bg-muted/10 text-muted">
                  {{ $activity->module }}
                </span>
                <span class="text-[10px] text-muted">IP: {{ $activity->ip_address }}</span>
              </div>
            </div>
            @empty
            <div class="px-6 py-12 text-center text-muted italic text-sm">No activity recorded yet</div>
            @endforelse
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
