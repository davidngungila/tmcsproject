@extends('layouts.app')

@section('title', 'Users - TmcsSmart')
@section('page-title', 'User Management')
@section('breadcrumb', 'TmcsSmart / Administration / Users')

@section('content')
<div class="animate-in">
  <!-- PAGE HEADER -->
  <div class="flex items-center justify-between mb-6">
    <div>
      <h2 class="text-lg font-bold">User Management</h2>
      <p class="text-sm text-muted mt-1">Manage system users and their roles</p>
    </div>
    @if(auth()->user()->hasPermission('users.create'))
    <a href="{{ route('users.create') }}" class="btn btn-primary">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
      Add New User
    </a>
    @endif
  </div>

  <!-- USERS TABLE -->
  <div class="card">
    <div class="card-header border-b">
      <div class="card-title">System Users</div>
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Role</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($users as $user)
          <tr>
            <td>
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
            <td>{{ $user->email }}</td>
            <td>{{ $user->phone ?? 'N/A' }}</td>
            <td>
              @foreach($user->roles as $role)
                <span class="badge bg-blue-100 text-blue-700">{{ $role->display_name }}</span>
              @endforeach
            </td>
            <td>
              <span class="badge {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                {{ $user->is_active ? 'Active' : 'Inactive' }}
              </span>
            </td>
            <td class="px-4 py-3">
              <div class="flex items-center gap-1">
                @if(auth()->user()->hasPermission('users.edit'))
                <a href="{{ route('users.edit', $user->id) }}" class="p-1.5 rounded-lg text-muted hover:text-blue-600 hover:bg-blue-500/10 transition-all" title="Edit User">
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </a>
                
                <button type="button" class="p-1.5 rounded-lg text-muted hover:text-purple-600 hover:bg-purple-500/10 transition-all" title="Reset Password" onclick="openResetModal({{ $user->id }}, '{{ $user->name }}')">
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 7a2 2 0 012 2m-2 4a2 2 0 012-2m-2-4a2 2 0 01-2-2m-2 4h-3a2 2 0 00-2 2v7a2 2 0 002 2h2a2 2 0 002-2v-7a2 2 0 00-2-2m-2 4h.01"/></svg>
                </button>

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
    <div class="card-footer border-t">
      {{ $users->links() }}
    </div>
    @endif
  </div>
</div>

<!-- RESET PASSWORD MODAL -->
<div id="resetPasswordModal" class="fixed inset-0 bg-black/60 hidden z-50 items-center justify-center p-4 backdrop-blur-sm">
  <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden animate-in">
    <div class="p-6 border-b flex items-center justify-between bg-muted/5">
      <h3 class="font-bold text-lg">Reset Password</h3>
      <button onclick="closeResetModal()" class="text-muted hover:text-primary">&times;</button>
    </div>
    <form id="resetPasswordForm" method="POST" class="p-6 space-y-4">
      @csrf
      <p class="text-[11px] text-muted">Setting a new password for <span id="resetUserName" class="font-bold text-primary"></span>. The user will be forced to change this password on their next login.</p>
      
      <div class="form-group">
        <label class="form-label">New Password</label>
        <input type="password" name="password" class="form-control" placeholder="Min 8 characters" required>
      </div>
      
      <div class="form-group">
        <label class="form-label">Confirm New Password</label>
        <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat new password" required>
      </div>

      <div class="flex justify-end gap-3 mt-6">
        <button type="button" onclick="closeResetModal()" class="btn btn-secondary">Cancel</button>
        <button type="submit" class="btn btn-primary px-8">Reset Password</button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
  function openResetModal(userId, userName) {
    const modal = document.getElementById('resetPasswordModal');
    const form = document.getElementById('resetPasswordForm');
    const nameSpan = document.getElementById('resetUserName');
    
    nameSpan.textContent = userName;
    form.action = `/users/${userId}/reset-password`;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
  }

  function closeResetModal() {
    const modal = document.getElementById('resetPasswordModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
  }
</script>
@endpush
@endsection
