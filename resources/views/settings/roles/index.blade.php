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
    <button class="btn btn-primary" onclick="openNewRoleModal()">
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
          @if($role->name !== 'admin' && $role->name !== 'chaplain')
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
        <h4 class="text-[10px] font-black uppercase tracking-widest text-muted mb-3">Core Permissions</h4>
        <div class="flex flex-wrap gap-1.5">
          @forelse($role->permissions->take(8) as $perm)
          <span class="px-2 py-0.5 rounded-full bg-blue-500/10 text-blue-600 text-[10px] font-bold">
            {{ str_replace('.', ': ', $perm->name) }}
          </span>
          @empty
          <span class="text-xs text-muted italic">No permissions assigned.</span>
          @endforelse
          @if($role->permissions->count() > 8)
          <span class="px-2 py-0.5 rounded-full bg-muted/10 text-muted text-[10px] font-bold">+{{ $role->permissions->count() - 8 }} more</span>
          @endif
        </div>
      </div>
      <div class="p-4 bg-muted/5 border-t mt-auto">
        <button class="btn btn-secondary btn-sm w-full justify-center" onclick="editPermissions({{ $role->id }}, '{{ $role->name }}', {{ json_encode($role->permissions->pluck('id')) }})">Manage All Permissions</button>
      </div>
    </div>
    @endforeach
  </div>
</div>

<!-- NEW ROLE MODAL -->
<div id="newRoleModal" class="fixed inset-0 bg-black/60 hidden z-50 items-center justify-center p-4 backdrop-blur-sm">
  <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden animate-in">
    <div class="p-6 border-b flex items-center justify-between bg-muted/5">
      <h3 class="font-bold text-lg">Create New Role</h3>
      <button onclick="closeNewRoleModal()" class="text-muted hover:text-primary">&times;</button>
    </div>
    <form action="{{ route('settings.roles.store') }}" method="POST" class="p-6 space-y-4">
      @csrf
      <div class="form-group">
        <label class="form-label">Role Name</label>
        <input type="text" name="name" class="form-control" placeholder="e.g. Accountant" required>
        <p class="text-[10px] text-muted mt-1">Role names should be unique and descriptive.</p>
      </div>
      <div class="flex justify-end gap-3 mt-6">
        <button type="button" onclick="closeNewRoleModal()" class="btn btn-secondary">Cancel</button>
        <button type="submit" class="btn btn-primary px-8">Create Role</button>
      </div>
    </form>
  </div>
</div>

<!-- PERMISSIONS MODAL -->
<div id="permissionsModal" class="fixed inset-0 bg-black/60 hidden z-50 items-center justify-center p-4 backdrop-blur-sm">
  <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col animate-in">
    <div class="p-6 border-b flex items-center justify-between bg-muted/5">
      <div>
        <h3 class="font-bold text-lg">Manage Permissions</h3>
        <p class="text-xs text-muted">Assigning permissions to: <span id="targetRoleName" class="font-bold text-primary"></span></p>
      </div>
      <button onclick="closePermissionsModal()" class="text-muted hover:text-primary">&times;</button>
    </div>
    
    <form id="permissionsForm" method="POST" class="flex flex-col flex-grow overflow-hidden">
      @csrf
      @method('PUT')
      
      <div class="p-6 overflow-y-auto flex-grow">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          @foreach($permissions as $module => $modulePermissions)
          <div class="space-y-3">
            <h4 class="text-xs font-black uppercase tracking-widest text-primary border-b pb-2 flex items-center justify-between">
              {{ ucfirst($module) }}
              <button type="button" class="text-[10px] text-blue-600 hover:underline normal-case font-bold" onclick="toggleModule('{{ $module }}')">Toggle All</button>
            </h4>
            <div class="grid grid-cols-1 gap-2">
              @foreach($modulePermissions as $perm)
              <label class="flex items-center gap-3 p-2 rounded hover:bg-muted/5 cursor-pointer transition-colors border border-transparent hover:border-muted/10">
                <input type="checkbox" name="permissions[]" value="{{ $perm->id }}" class="w-4 h-4 rounded text-blue-600 module-{{ $module }}">
                <div class="flex flex-col">
                  <span class="text-xs font-bold">{{ $perm->display_name }}</span>
                  <span class="text-[10px] text-muted">{{ $perm->description ?: 'No description provided.' }}</span>
                </div>
              </label>
              @endforeach
            </div>
          </div>
          @endforeach
        </div>
      </div>

      <div class="p-6 bg-muted/5 border-t flex items-center justify-between">
        <div class="text-[10px] text-muted font-bold uppercase tracking-widest">
          Changes will take effect immediately upon saving.
        </div>
        <div class="flex gap-3">
          <button type="button" onclick="closePermissionsModal()" class="btn btn-secondary">Cancel</button>
          <button type="submit" class="btn btn-primary px-10">Save Permissions</button>
        </div>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
  function openNewRoleModal() {
    const modal = document.getElementById('newRoleModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
  }

  function closeNewRoleModal() {
    const modal = document.getElementById('newRoleModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
  }

  function editPermissions(roleId, roleName, assignedIds) {
    const modal = document.getElementById('permissionsModal');
    const form = document.getElementById('permissionsForm');
    const nameSpan = document.getElementById('targetRoleName');
    
    nameSpan.textContent = roleName;
    form.action = `/settings/roles/${roleId}`;
    
    // Clear all checkboxes first
    form.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
    
    // Check assigned permissions
    assignedIds.forEach(id => {
      const cb = form.querySelector(`input[value="${id}"]`);
      if (cb) cb.checked = true;
    });
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
  }

  function closePermissionsModal() {
    const modal = document.getElementById('permissionsModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
  }

  function toggleModule(module) {
    const checkboxes = document.querySelectorAll(`.module-${module}`);
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    checkboxes.forEach(cb => cb.checked = !allChecked);
  }

  // Close modals on escape
  window.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      closeNewRoleModal();
      closePermissionsModal();
    }
  });
</script>
@endpush
@endsection
