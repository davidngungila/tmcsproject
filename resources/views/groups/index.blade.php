@extends('layouts.app')

@section('title', 'Groups - TmcsSmart')
@section('page-title', 'Group Management')
@section('breadcrumb', 'TmcsSmart / Groups')

@section('content')
<div class="animate-in">
  <!-- PAGE HEADER -->
  <div class="flex items-center justify-between mb-6">
    <div>
      <h2 class="text-lg font-bold">Group Management</h2>
      <p class="text-sm text-muted mt-1">Manage church groups and communities</p>
    </div>
    <div class="flex gap-3">
      @if(auth()->user()->hasPermission('groups.export'))
      <button class="btn btn-secondary" onclick="exportGroups()">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Export
      </button>
      @endif
      @if(auth()->user()->hasPermission('groups.create'))
      <a href="{{ route('groups.create') }}" class="btn btn-primary">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        Create Group
      </a>
      @endif
    </div>
  </div>

  <!-- GROUP STATISTICS -->
  <div class="stat-grid mb-6">
    <div class="stat-card green">
      <div class="stat-icon green">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
      </div>
      <div class="stat-value">{{ $totalGroups }}</div>
      <div class="stat-label">Total Groups</div>
    </div>

    <div class="stat-card gold">
      <div class="stat-icon gold">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
      </div>
      <div class="stat-value">{{ $totalMembers }}</div>
      <div class="stat-label">Total Members</div>
    </div>

    <div class="stat-card blue">
      <div class="stat-icon blue">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
      </div>
      <div class="stat-value">{{ $activeGroups }}</div>
      <div class="stat-label">Active Groups</div>
    </div>

    <div class="stat-card red">
      <div class="stat-icon red">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
      </div>
      <div class="stat-value">{{ $upcomingEvents }}</div>
      <div class="stat-label">Upcoming Events</div>
    </div>
  </div>

  <!-- FILTERS -->
  <div class="card mb-4">
    <div class="card-body">
      <div class="filter-row">
        <input type="text" class="form-control search-input" placeholder="Search groups..." id="searchInput">
        <select class="form-control filter-select" id="typeFilter">
          <option value="">All Types</option>
          <option value="Ministry">Ministry</option>
          <option value="Fellowship">Fellowship</option>
          <option value="Education">Education</option>
          <option value="Service">Service Group</option>
        </select>
        <select class="form-control filter-select" id="statusFilter">
          <option value="">All Status</option>
          <option value="1">Active</option>
          <option value="0">Inactive</option>
        </select>
        <button class="btn btn-secondary" onclick="resetFilters()">Reset</button>
      </div>
    </div>
  </div>

  <!-- GROUPS TABLE -->
  <div class="card mb-6 overflow-hidden">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="w-full">
          <thead>
            <tr class="text-left border-b border-light bg-light/30">
              <th class="p-4 text-xs font-bold text-muted uppercase">Group Name</th>
              <th class="p-4 text-xs font-bold text-muted uppercase">Type</th>
              <th class="p-4 text-xs font-bold text-muted uppercase">Status</th>
              <th class="p-4 text-xs font-bold text-muted uppercase text-center">Members</th>
              <th class="p-4 text-xs font-bold text-muted uppercase">Leader</th>
              <th class="p-4 text-xs font-bold text-muted uppercase">Formed Date</th>
              <th class="p-4 text-xs font-bold text-muted uppercase text-right">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($groups as $group)
            <tr class="border-b border-light hover:bg-light/30 transition-all">
              <td class="p-4">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-lg bg-green-50 text-green-600 flex items-center justify-center font-bold">
                    {{ substr($group->name, 0, 2) }}
                  </div>
                  <div>
                    <div class="font-bold text-sm">{{ $group->name }}</div>
                    <div class="text-[10px] text-muted truncate max-w-[150px]">{{ $group->description }}</div>
                  </div>
                </div>
              </td>
              <td class="p-4 text-sm">{{ $group->type }}</td>
              <td class="p-4">
                <span class="badge {{ $group->is_active ? 'green' : 'red' }} scale-90 origin-left">
                  {{ $group->is_active ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class="p-4 text-sm text-center font-bold">{{ $group->members->count() }}</td>
              <td class="p-4 text-sm">
                @if($group->leader)
                  <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-light flex items-center justify-center text-[10px] font-bold">
                      {{ substr($group->leader->full_name, 0, 2) }}
                    </div>
                    {{ $group->leader->full_name }}
                  </div>
                @else
                  <span class="text-muted italic">Not assigned</span>
                @endif
              </td>
              <td class="p-4 text-sm text-muted">
                {{ $group->created_at->format('M d, Y') }}
              </td>
              <td class="p-4">
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('groups.show', $group->id) }}" class="btn btn-secondary btn-sm p-1.5" title="View Details">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                  </a>
                  <a href="{{ route('groups.edit', $group->id) }}" class="btn btn-secondary btn-sm p-1.5" title="Edit Group">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                  </a>
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="7" class="p-12 text-center text-muted">
                <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 12px;display:block;">
                  <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <p>No groups found</p>
                @if(auth()->user()->hasPermission('groups.create'))
                <a href="{{ route('groups.create') }}" class="btn btn-primary mt-4">Create First Group</a>
                @endif
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- PAGINATION -->
  {{ $groups->links() }}
</div>
@endsection

@push('scripts')
<script>
function exportGroups() {
  window.open('/groups/export', '_blank');
}

function resetFilters() {
  document.getElementById('searchInput').value = '';
  document.getElementById('typeFilter').value = '';
  document.getElementById('statusFilter').value = '';
  location.href = '{{ route('groups.index') }}';
}

// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
  const search = e.target.value;
  const url = new URL(window.location);
  if (search) {
    url.searchParams.set('search', search);
  } else {
    url.searchParams.delete('search');
  }
  window.location = url.toString();
});

// Filter functionality
['typeFilter', 'statusFilter'].forEach(id => {
  document.getElementById(id).addEventListener('change', function(e) {
    const value = e.target.value;
    const url = new URL(window.location);
    const param = id.replace('Filter', '');
    if (value) {
      url.searchParams.set(param, value);
    } else {
      url.searchParams.delete(param);
    }
    window.location = url.toString();
  });
});
</script>
@endpush
