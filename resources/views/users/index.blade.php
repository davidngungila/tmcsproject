@extends('layouts.app')

@section('title', 'Users - TmcsSmart')
@section('page-title', 'User Management')
@section('breadcrumb', 'TmcsSmart / Administration / Users')

@section('content')
<div class="animate-in space-y-6">
  <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
      <h2 class="text-2xl font-bold tracking-tight">User Accounts</h2>
      <p class="text-sm text-muted mt-1">Manage system access, roles, and security for all users.</p>
    </div>
    @if(auth()->user()->hasPermission('users.create'))
    <a href="{{ route('users.create') }}" class="btn btn-primary">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
      Add New User
    </a>
    @endif
  </div>

  <div class="card bg-white shadow-sm overflow-hidden">
    <div class="p-6 border-b bg-muted/5 flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div class="flex-grow max-w-md">
        <div class="relative">
          <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-muted">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
          </span>
          <input type="text" id="userSearch" placeholder="Search by name, email or phone..." class="form-control pl-10 text-sm" value="{{ request('search') }}">
        </div>
      </div>
      
      <div class="flex items-center gap-2">
        <select id="roleFilter" class="form-control text-sm w-40">
          <option value="">All Roles</option>
          @foreach($roles as $role)
            <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
          @endforeach
        </select>
        
        <select id="statusFilter" class="form-control text-sm w-32">
          <option value="">All Status</option>
          <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
          <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        
        <button onclick="applyFilters()" class="btn btn-secondary btn-sm">Filter</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">Clear</a>
      </div>
    </div>

    <div class="table-wrap overflow-x-auto">
      <table class="w-full" style="min-width: 900px;">
        <thead>
          <tr class="bg-muted/5 text-[10px] font-black uppercase tracking-widest text-muted">
            <th class="px-6 py-4 text-left">User</th>
            <th class="px-6 py-4 text-left">Email</th>
            <th class="px-6 py-4 text-left">Phone</th>
            <th class="px-6 py-4 text-left">Role</th>
            <th class="px-6 py-4 text-left">Status</th>
            <th class="px-6 py-4 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-muted/10" id="usersTableBody">
          @forelse($users as $user)
          <tr class="hover:bg-primary/5 transition-colors text-xs user-row" 
              data-name="{{ strtolower($user->name) }}" 
              data-email="{{ strtolower($user->email) }}" 
              data-phone="{{ $user->phone }}"
              data-role="{{ $user->roles->first()?->id }}"
              data-status="{{ $user->is_active ? 'active' : 'inactive' }}">
            <td class="px-6 py-4">
              <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-full bg-green-500/10 text-green-600 flex items-center justify-center overflow-hidden text-[10px] font-bold border border-green-500/20">
                  @if($user->profile_image)
                    <img src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                  @else
                    {{ substr($user->name, 0, 1) }}
                  @endif
                </div>
                <div style="font-weight:700;font-size:12px;" class="text-primary">{{ $user->name }}</div>
              </div>
            </td>
            <td class="px-6 py-4 text-muted">{{ $user->email }}</td>
            <td class="px-6 py-4 text-muted">{{ $user->phone ?: 'N/A' }}</td>
            <td class="px-6 py-4">
              @foreach($user->roles as $role)
                <span class="px-2 py-0.5 rounded bg-muted/10 text-muted text-[10px] font-bold uppercase">{{ $role->name }}</span>
              @endforeach
            </td>
            <td class="px-6 py-4">
              <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full {{ $user->is_active ? 'bg-green-500/10 text-green-600' : 'bg-red-500/10 text-red-600' }} text-[10px] font-black uppercase tracking-wider">
                <span class="w-1.5 h-1.5 rounded-full {{ $user->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                {{ $user->is_active ? 'Active' : 'Inactive' }}
              </span>
            </td>
            <td class="px-6 py-4">
              <div class="flex items-center justify-end gap-1">
                <!-- VIEW USER (Always available for admin-level users) -->
                <a href="{{ route('users.show', $user->id) }}" class="p-1.5 rounded-lg text-muted hover:text-green-600 hover:bg-green-500/10 transition-all" title="View Details">
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </a>

                @if(auth()->user()->hasPermission('users.edit'))
                <a href="{{ route('users.edit', $user->id) }}" class="p-1.5 rounded-lg text-muted hover:text-blue-600 hover:bg-blue-500/10 transition-all" title="Edit User">
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </a>
                
                <form action="{{ route('users.reset-password', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('⚠️ RESET PASSWORD CONFIRMATION\n\nAre you sure you want to reset the password for {{ $user->name }}?\n\nThis will:\n1. Generate a new random secure password\n2. Update the user account\n3. Send an email notification to {{ $user->email }} with the new credentials.\n\nContinue?')">
                  @csrf
                  <button type="submit" class="p-1.5 rounded-lg text-muted hover:text-amber-600 hover:bg-amber-500/10 transition-all" title="Reset Password & Send Email">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                    </svg>
                  </button>
                </form>

                <form action="{{ route('users.toggle-status', $user->id) }}" method="POST" class="inline">
                  @csrf
                  <button type="submit" class="p-1.5 rounded-lg {{ $user->is_active ? 'text-muted hover:text-amber-600 hover:bg-amber-500/10' : 'text-muted hover:text-green-600 hover:bg-green-500/10' }} transition-all" title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}">
                    @if($user->is_active)
                      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                    @else
                      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @endif
                  </button>
                </form>
                @endif

                @if(auth()->user()->hasPermission('users.delete') && $user->id !== auth()->id())
                <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?')" class="inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="p-1.5 rounded-lg text-muted hover:text-red-600 hover:bg-red-500/10 transition-all" title="Delete User">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  </button>
                </form>
                @endif
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="text-center py-10 text-muted">No users found</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($users->hasPages())
    <div class="card-footer border-t bg-muted/5 px-6 py-4">
      {{ $users->appends(request()->query())->links() }}
    </div>
    @endif
  </div>
</div>

@push('scripts')
<script>
  // Live Search & Local Filtering
  document.getElementById('userSearch').addEventListener('input', function(e) {
    const term = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('.user-row');
    
    rows.forEach(row => {
      const name = row.dataset.name;
      const email = row.dataset.email;
      const phone = row.dataset.phone;
      
      if (name.includes(term) || email.includes(term) || (phone && phone.includes(term))) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  });

  function applyFilters() {
    const search = document.getElementById('userSearch').value;
    const role = document.getElementById('roleFilter').value;
    const status = document.getElementById('statusFilter').value;
    
    let url = new URL(window.location.href);
    if (search) url.searchParams.set('search', search); else url.searchParams.delete('search');
    if (role) url.searchParams.set('role', role); else url.searchParams.delete('role');
    if (status) url.searchParams.set('status', status); else url.searchParams.delete('status');
    
    window.location.href = url.toString();
  }
</script>
@endpush
@endsection
