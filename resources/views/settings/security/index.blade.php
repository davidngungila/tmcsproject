@extends('layouts.app')

@section('title', 'Security Controls - TmcsSmart')
@section('page-title', 'Security & Access Control')
@section('breadcrumb', 'TmcsSmart / Settings / Security')

@section('content')
<div class="animate-in space-y-6 max-w-5xl mx-auto">
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- SYSTEM CONTROLS -->
    <div class="md:col-span-2 space-y-6">
      <div class="card bg-white shadow-sm overflow-hidden">
        <div class="card-header border-b p-6">
          <h3 class="font-bold text-lg">System-Wide Security</h3>
          <p class="text-xs text-muted">Manage global security policies and authentication requirements.</p>
        </div>
        <div class="card-body p-6">
          <form action="{{ route('settings.security.update') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="form-group">
                <label class="text-[10px] font-black uppercase tracking-widest text-muted block mb-2">Two-Factor Authentication (2FA)</label>
                <select name="settings[enable_2fa]" class="form-control">
                  <option value="1" {{ SystemSetting::get('enable_2fa') == '1' ? 'selected' : '' }}>Enabled (Recommended)</option>
                  <option value="0" {{ SystemSetting::get('enable_2fa') == '0' ? 'selected' : '' }}>Disabled</option>
                </select>
              </div>

              <div class="form-group">
                <label class="text-[10px] font-black uppercase tracking-widest text-muted block mb-2">Session Timeout (Minutes)</label>
                <input type="number" name="settings[session_timeout]" class="form-control" value="{{ SystemSetting::get('session_timeout', 120) }}">
              </div>

              <div class="form-group">
                <label class="text-[10px] font-black uppercase tracking-widest text-muted block mb-2">Max Login Attempts</label>
                <input type="number" name="settings[max_login_attempts]" class="form-control" value="{{ SystemSetting::get('max_login_attempts', 5) }}">
              </div>

              <div class="form-group">
                <label class="text-[10px] font-black uppercase tracking-widest text-muted block mb-2">Lockout Duration (Minutes)</label>
                <input type="number" name="settings[lockout_duration]" class="form-control" value="{{ SystemSetting::get('lockout_duration', 30) }}">
              </div>
            </div>

            <div class="pt-6 border-t border-dashed">
              <div class="flex items-center justify-between p-4 bg-red-50 rounded-lg border border-red-100">
                <div>
                  <h4 class="text-sm font-bold text-red-700">Maintenance Mode</h4>
                  <p class="text-[11px] text-red-600">While active, only administrators can access the system.</p>
                </div>
                <select name="settings[maintenance_mode]" class="form-control w-32 border-red-200">
                  <option value="0" {{ !app()->isDownForMaintenance() ? 'selected' : '' }}>Off</option>
                  <option value="1" {{ app()->isDownForMaintenance() ? 'selected' : '' }}>On</option>
                </select>
              </div>
            </div>

            <div class="flex justify-end">
              <button type="submit" class="btn btn-primary px-10">Save Security Policy</button>
            </div>
          </form>
        </div>
      </div>

      <div class="card bg-white shadow-sm overflow-hidden">
        <div class="card-header border-b p-6">
          <h3 class="font-bold text-lg">IP Blocking</h3>
          <p class="text-xs text-muted">Manually block suspicious IP addresses from accessing the system.</p>
        </div>
        <div class="card-body p-6">
          <form action="{{ route('settings.security.block-ip') }}" method="POST" class="flex gap-3">
            @csrf
            <input type="text" name="ip" class="form-control" placeholder="e.g. 192.168.1.100" required>
            <button type="submit" class="btn btn-secondary flex-shrink-0">Block IP</button>
          </form>

          <div class="mt-6 space-y-2">
            @php $blocked = SystemSetting::get('blocked_ips', []); @endphp
            @forelse($blocked as $ip)
            <div class="flex items-center justify-between p-3 bg-muted/5 rounded-lg border text-xs">
              <span class="font-mono">{{ $ip }}</span>
              <button class="text-red-600 hover:underline">Unblock</button>
            </div>
            @empty
            <p class="text-xs text-muted italic text-center py-4">No IP addresses blocked.</p>
            @endforelse
          </div>
        </div>
      </div>
    </div>

    <!-- QUICK ACTIONS -->
    <div class="space-y-6">
      <div class="card bg-white shadow-sm p-6">
        <h3 class="font-bold text-sm uppercase tracking-widest text-muted mb-4">Quick Emergency Actions</h3>
        <div class="space-y-3">
          <form action="{{ route('settings.security.force-logout-all') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-secondary w-full justify-start gap-3 text-red-600 border-red-100 hover:bg-red-50" onclick="return confirm('Force all other users to logout?')">
              <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
              Force Logout All Users
            </button>
          </form>
          
          <button class="btn btn-secondary w-full justify-start gap-3">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            Clear Authentication Cache
          </button>
        </div>
      </div>

      <div class="card bg-white shadow-sm p-6">
        <h3 class="font-bold text-sm uppercase tracking-widest text-muted mb-4">Security Status</h3>
        <div class="space-y-4">
          <div class="flex items-center justify-between">
            <span class="text-xs text-muted">SSL Status</span>
            <span class="text-[10px] font-black uppercase tracking-widest text-green-600 px-2 py-0.5 rounded bg-green-50">Active</span>
          </div>
          <div class="flex items-center justify-between">
            <span class="text-xs text-muted">Environment</span>
            <span class="text-[10px] font-black uppercase tracking-widest text-blue-600 px-2 py-0.5 rounded bg-blue-50">{{ config('app.env') }}</span>
          </div>
          <div class="flex items-center justify-between">
            <span class="text-xs text-muted">Database Logs</span>
            <span class="text-[10px] font-black uppercase tracking-widest text-green-600 px-2 py-0.5 rounded bg-green-50">Encrypted</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
