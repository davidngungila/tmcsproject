@extends('layouts.app')

@section('title', 'Roles & Permissions - TmcsSmart')
@section('page-title', 'Role Based Access Control')
@section('breadcrumb', 'TmcsSmart / Settings / Roles')

@section('content')
<div class="animate-in space-y-6">
  <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
      <h2 class="text-2xl font-bold tracking-tight">System Roles</h2>
      <p class="text-sm text-muted mt-1">Manage user roles and their associated module permissions.</p>
    </div>
    <button class="btn btn-primary" onclick="document.getElementById('newRoleModal').classList.remove('hidden')">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
      Create New Role
    </button>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($roles as $role)
    <div class="card bg-white shadow-sm overflow-hidden flex flex-col">
      <div class="p-6 border-b bg-muted/5 flex items-center justify-between">
        <div>
          <h3 class="font-bold text-lg uppercase tracking-tight text-primary">{{ $role->name }}</h3>
          <p class="text-[10px] text-muted">{{ $role->users()->count() }} users assigned</p>
        </div>
        <div class="flex gap-1">
          <form action="{{ route('settings.roles.clone', $role->id) }}" method="POST">
            @csrf
            <button type="submit" class="p-1.5 rounded hover:bg-muted/10 text-muted" title="Clone Role">
              <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7v8a2 2 0 002 2h6a2 2 0 002-2V7a2 2 0 00-2-2h-6a2 2 0 00-2 2z"/><path d="M8 5H6a2 2 0 00-2 2v8a2 2 0 002 2h2"/></svg>
            </button>
          </form>
          @if($role->name !== 'admin')
          <form action="{{ route('settings.roles.destroy', $role->id) }}" method="POST">
            @csrf @method('DELETE')
            <button type="submit" class="p-1.5 rounded hover:bg-muted/10 text-red-600" onclick="return confirm('Delete this role?')">
              <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
          </form>
          @endif
        </div>
      </div>
      <div class="p-6 flex-grow">
        <h4 class="text-[10px] font-black uppercase tracking-widest text-muted mb-3">Module Permissions</h4>
        <div class="flex flex-wrap gap-1.5">
          @forelse($role->permissions as $perm)
          <span class="px-2 py-0.5 rounded-full bg-blue-500/10 text-blue-600 text-[10px] font-bold">
            {{ str_replace('.', ': ', $perm->name) }}
          </span>
          @empty
          <span class="text-xs text-muted italic">No permissions assigned.</span>
          @endforelse
        </div>
      </div>
      <div class="p-4 bg-muted/5 border-t mt-auto">
        <button class="btn btn-secondary btn-sm w-full justify-center" onclick="editPermissions({{ $role->id }}, '{{ $role->name }}')">Manage Permissions</button>
      </div>
    </div>
    @endforeach
  </div>
</div>

<!-- NEW ROLE MODAL -->
<div id="newRoleModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden animate-in">
    <div class="p-6 border-b flex items-center justify-between">
      <h3 class="font-bold text-lg">Create New Role</h3>
      <button onclick="document.getElementById('newRoleModal').classList.add('hidden')" class="text-muted hover:text-primary">&times;</button>
    </div>
    <form action="{{ route('settings.roles.store') }}" method="POST" class="p-6 space-y-4">
      @csrf
      <div class="form-group">
        <label class="form-label">Role Name</label>
        <input type="text" name="name" class="form-control" placeholder="e.g. Accountant" required>
      </div>
      <div class="flex justify-end gap-3 mt-6">
        <button type="button" onclick="document.getElementById('newRoleModal').classList.add('hidden')" class="btn btn-secondary">Cancel</button>
        <button type="submit" class="btn btn-primary px-8">Create Role</button>
      </div>
    </form>
  </div>
</div>
@endsection
