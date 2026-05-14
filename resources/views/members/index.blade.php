@extends('layouts.app')

@section('title', 'Members - TmcsSmart')
@section('page-title', 'Member Management')
@section('breadcrumb', 'TmcsSmart / Members')

@section('content')
<div class="animate-in">
  <!-- PAGE HEADER -->
  <div class="flex items-center justify-between mb-6">
    <div>
      <h2 class="text-lg font-bold">Member Management</h2>
      <p class="text-sm text-muted mt-1">Manage church members and their information</p>
    </div>
    <div class="flex gap-3">
      @if(auth()->user()->hasPermission('members.import'))
      <button class="btn btn-secondary" onclick="showImportModal()">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
        Import Members
      </button>
      @endif
      @if(auth()->user()->hasPermission('members.export'))
      <button class="btn btn-secondary" onclick="exportMembers()">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Export
      </button>
      @endif
      @if(auth()->user()->hasPermission('members.create'))
      <a href="{{ route('members.create') }}" class="btn btn-primary">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
        Add New Member
      </a>
      @endif
    </div>
  </div>

  <!-- FILTERS -->
  <div class="card mb-4">
    <div class="card-body">
      <div class="filter-row">
        <input type="text" class="form-control search-input" placeholder="Search members..." id="searchInput">
        <select class="form-control filter-select" id="memberTypeFilter">
          <option value="">All Types</option>
          <option value="Student">Student</option>
          <option value="Non-Student">Non-Student</option>
          <option value="Employee">Employee</option>
          <option value="Child">Child</option>
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

  <!-- MEMBERS TABLE -->
  <div class="card">
    <div class="card-header">
      <div class="card-title">All Members</div>
      <div class="card-subtitle">{{ $members->count() }} total members</div>
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Reg. No.</th>
            <th>Member</th>
            <th>Type</th>
            <th>Contact</th>
            <th>Registration Date</th>
            <th>Groups</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($members as $member)
          <tr>
            <td class="mono text-sm">{{ $member->registration_number }}</td>
            <td>
              <div class="flex items-center gap-2">
                <div class="avatar overflow-hidden">
                  @if($member->photo)
                    <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->full_name }}" class="w-full h-full object-cover">
                  @else
                    {{ substr($member->full_name, 0, 2) }}
                  @endif
                </div>
                <div>
                  <div style="font-weight:600;font-size:13px;">{{ $member->full_name }}</div>
                  <div style="font-size:11px;color:var(--text-muted);">
                    {{ $member->baptismal_name ? 'Baptismal: ' . $member->baptismal_name : '' }}
                    {{ $member->category ? ' • ' . $member->category : '' }}
                  </div>
                </div>
              </div>
            </td>
            <td><span class="badge blue">{{ ucfirst($member->member_type) }}</span></td>
            <td>
              <div style="font-size:12px;">
                @if($member->phone)<div>{{ $member->phone }}</div>@endif
                @if($member->email)<div style="color:var(--text-muted);">{{ $member->email }}</div>@endif
              </div>
            </td>
            <td>{{ $member->registration_date->format('M d, Y') }}</td>
            <td>
              <div class="flex gap-1">
                @foreach($member->groups->take(2) as $group)
                <span class="badge green text-xs">{{ $group->name }}</span>
                @endforeach
                @if($member->groups->count() > 2)
                <span class="badge amber text-xs">+{{ $member->groups->count() - 2 }}</span>
                @endif
              </div>
            </td>
            <td>
              <span class="badge {{ $member->is_active ? 'green' : 'red' }}">
                {{ $member->is_active ? 'Active' : 'Inactive' }}
              </span>
            </td>
            <td>
              <div class="flex gap-1">
                <a href="{{ route('members.show', $member->id) }}" class="btn btn-ghost btn-sm" title="View">
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </a>
                @if(auth()->user()->hasPermission('members.edit'))
                <a href="{{ route('members.edit', $member->id) }}" class="btn btn-ghost btn-sm" title="Edit">
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </a>
                @endif
                @if(auth()->user()->hasPermission('members.delete'))
                <button class="btn btn-ghost btn-sm text-red" onclick="deleteMember({{ $member->id }})" title="Delete">
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
                @endif
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" class="text-center py-8 text-muted">No members found</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    
    <!-- PAGINATION -->
    <div class="card-footer">
      {{ $members->links() }}
    </div>
  </div>
</div>

<!-- VIEW MEMBER MODAL -->
<div class="modal-overlay" id="viewMemberModal">
  <div class="modal" style="width: 600px;">
    <div class="modal-header">
      <div><div class="card-title">Member Details</div><div class="card-subtitle">Complete member information</div></div>
      <div class="modal-close" onclick="closeModal('viewMemberModal')">✕</div>
    </div>
    <div class="modal-body" id="memberDetails">
      <!-- Content will be loaded dynamically -->
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('viewMemberModal')">Close</button>
    </div>
  </div>
</div>

<!-- IMPORT MODAL -->
<div class="modal-overlay" id="importModal">
  <div class="modal">
    <div class="modal-header">
      <div><div class="card-title">Import Members</div><div class="card-subtitle">Upload CSV or Excel file</div></div>
      <div class="modal-close" onclick="closeModal('importModal')">✕</div>
    </div>
    <div class="modal-body">
      <div class="upload-box" onclick="document.getElementById('importFile').click()">
        <svg width="32" height="32" fill="none" stroke="var(--text-muted)" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 12px;display:block;">
          <path d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
        </svg>
        <p style="font-size:14px;font-weight:600;color:var(--text-secondary);">Drop CSV/Excel file here or click to browse</p>
        <p style="font-size:12px;color:var(--text-muted);margin-top:4px;">Maximum file size: 10MB</p>
        <input type="file" id="importFile" accept=".csv,.xlsx,.xls" style="display:none;">
      </div>
      
      <div class="mt-4">
        <h4 class="font-bold mb-2">Required Columns:</h4>
        <div class="text-sm text-muted">
          <div>• full_name (required)</div>
          <div>• email (optional)</div>
          <div>• phone (optional)</div>
          <div>• member_type (required: student/non-student/employee/child)</div>
          <div>• date_of_birth (required: YYYY-MM-DD)</div>
          <div>• address (required)</div>
          <div>• baptismal_name (optional)</div>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('importModal')">Cancel</button>
      <button class="btn btn-primary" onclick="importMembers()">Import Members</button>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
function viewMember(memberId) {
  fetch(`/members/${memberId}/show`)
    .then(response => response.text())
    .then(html => {
      document.getElementById('memberDetails').innerHTML = html;
      document.getElementById('viewMemberModal').classList.add('open');
    });
}

function deleteMember(memberId) {
  if (confirm('Are you sure you want to delete this member? This action cannot be undone.')) {
    fetch(`/members/${memberId}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        showToast('Member deleted successfully', 'success');
        location.reload();
      } else {
        showToast(data.message || 'Error deleting member', 'error');
      }
    });
  }
}

function showImportModal() {
  document.getElementById('importModal').classList.add('open');
}

function importMembers() {
  const fileInput = document.getElementById('importFile');
  if (!fileInput.files.length) {
    showToast('Please select a file to import', 'warning');
    return;
  }
  
  const formData = new FormData();
  formData.append('file', fileInput.files[0]);
  
  fetch('/members/import', {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      showToast(`Successfully imported ${data.imported} members`, 'success');
      closeModal('importModal');
      location.reload();
    } else {
      showToast(data.message || 'Error importing members', 'error');
    }
  });
}

function exportMembers() {
  window.open('/members/export', '_blank');
}

function resetFilters() {
  document.getElementById('searchInput').value = '';
  document.getElementById('memberTypeFilter').value = '';
  document.getElementById('statusFilter').value = '';
  location.href = '{{ route('members.index') }}';
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
document.getElementById('memberTypeFilter').addEventListener('change', function(e) {
  const type = e.target.value;
  const url = new URL(window.location);
  if (type) {
    url.searchParams.set('type', type);
  } else {
    url.searchParams.delete('type');
  }
  window.location = url.toString();
});

document.getElementById('statusFilter').addEventListener('change', function(e) {
  const status = e.target.value;
  const url = new URL(window.location);
  if (status) {
    url.searchParams.set('status', status);
  } else {
    url.searchParams.delete('status');
  }
  window.location = url.toString();
});
</script>
@endpush
