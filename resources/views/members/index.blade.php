@extends('layouts.app')

@section('title', 'Members - TmcsSmart')
@section('page-title', 'Member Management')
@section('breadcrumb', 'TmcsSmart / Members')

@section('content')
<div class="animate-in space-y-6">
  <!-- PAGE HEADER -->
  <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-2">
    <div>
      <h2 class="text-2xl font-bold tracking-tight">Member Directory</h2>
      <p class="text-sm text-muted mt-1">Manage and organize church members and their detailed records.</p>
    </div>
    <div class="flex flex-wrap gap-3">
      @if(auth()->user()->hasPermission('members.import'))
      <button class="btn btn-secondary flex items-center gap-2 px-4" onclick="showImportModal()">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
        <span>Import</span>
      </button>
      @endif
      @if(auth()->user()->hasPermission('members.export'))
      <button class="btn btn-secondary flex items-center gap-2 px-4" onclick="exportMembers()">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        <span>Export</span>
      </button>
      @endif
      @if(auth()->user()->hasPermission('members.create'))
      <a href="{{ route('members.create') }}" class="btn btn-primary flex items-center gap-2 px-6 shadow-lg shadow-primary/20">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
        <span>Add Member</span>
      </a>
      @endif
    </div>
  </div>

  @if(session('import_errors'))
  <div class="card bg-red-500/10 border-red-500/20 mb-6">
    <div class="card-body p-4">
      <div class="flex items-center gap-2 text-red-600 mb-2">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        <span class="font-bold">Import Issues Found</span>
      </div>
      <div class="max-h-32 overflow-y-auto text-xs text-red-500 space-y-1 custom-scrollbar">
        @foreach(session('import_errors') as $error)
          <div>• {{ $error }}</div>
        @endforeach
      </div>
    </div>
  </div>
  @endif

  <!-- FILTERS -->
  <div class="card shadow-sm border-muted/10 overflow-visible">
    <div class="card-body p-4">
      <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-5 gap-4">
        <div class="md:col-span-2 lg:col-span-2 relative">
          <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-muted">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
          </span>
          <input type="text" class="form-control pl-9" placeholder="Search by name, email or reg number..." id="searchInput" value="{{ request('search') }}">
        </div>
        <div>
          <select class="form-control" id="memberTypeFilter">
            <option value="">All Categories</option>
            @php $categories = \App\Models\MemberCategory::where('is_active', true)->get(); @endphp
            @foreach($categories as $category)
              <option value="{{ $category->name }}" {{ request('type') == $category->name ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <select class="form-control" id="statusFilter">
            <option value="">All Status</option>
            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
          </select>
        </div>
        <div class="flex gap-2">
          <button class="btn btn-primary flex-1" onclick="applyFilters()">Filter</button>
          <button class="btn btn-secondary px-3" onclick="resetFilters()" title="Reset Filters">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- MEMBERS TABLE -->
  <div class="card shadow-sm border-muted/10 overflow-hidden">
    <div class="table-wrap">
      <table class="w-full">
        <thead>
          <tr class="bg-muted/5">
            <th class="px-6 py-4 text-left text-[10px] font-black uppercase tracking-widest text-muted">Registration</th>
            <th class="px-6 py-4 text-left text-[10px] font-black uppercase tracking-widest text-muted">Member Details</th>
            <th class="px-6 py-4 text-left text-[10px] font-black uppercase tracking-widest text-muted">Category</th>
            <th class="px-6 py-4 text-left text-[10px] font-black uppercase tracking-widest text-muted">Contact Info</th>
            <th class="px-6 py-4 text-left text-[10px] font-black uppercase tracking-widest text-muted">Groups</th>
            <th class="px-6 py-4 text-left text-[10px] font-black uppercase tracking-widest text-muted">Status</th>
            <th class="px-6 py-4 text-right text-[10px] font-black uppercase tracking-widest text-muted">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-muted/10">
          @forelse($members as $member)
          <tr class="hover:bg-primary/5 transition-colors">
            <td class="px-6 py-4">
              <div class="mono text-xs font-bold text-primary">{{ $member->registration_number }}</div>
              <div class="text-[10px] text-muted mt-1">{{ $member->registration_date->format('M d, Y') }}</div>
            </td>
            <td class="px-6 py-4">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl overflow-hidden bg-primary/10 flex-shrink-0 shadow-sm">
                  @if($member->photo)
                    <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->full_name }}" class="w-full h-full object-cover">
                  @else
                    <div class="w-full h-full flex items-center justify-center text-primary font-bold text-sm">
                      {{ substr($member->full_name, 0, 2) }}
                    </div>
                  @endif
                </div>
                <div>
                  <div class="text-sm font-bold text-primary">{{ $member->full_name }}</div>
                  <div class="text-[10px] text-muted">{{ $member->baptismal_name ? 'Baptismal: ' . $member->baptismal_name : 'No baptismal name' }}</div>
                </div>
              </div>
            </td>
            <td class="px-6 py-4">
              <span class="px-2.5 py-1 rounded-full bg-blue-500/10 text-blue-600 text-[10px] font-black uppercase tracking-wider">
                {{ $member->category ? $member->category->name : $member->member_type }}
              </span>
            </td>
            <td class="px-6 py-4">
              <div class="text-xs font-medium">{{ $member->phone ?? 'No Phone' }}</div>
              <div class="text-[10px] text-muted mt-0.5">{{ $member->email ?? 'No Email' }}</div>
            </td>
            <td class="px-6 py-4">
              <div class="flex flex-wrap gap-1">
                @forelse($member->groups->take(2) as $group)
                <span class="px-2 py-0.5 rounded-md bg-green-500/10 text-green-600 text-[9px] font-bold uppercase">{{ $group->name }}</span>
                @empty
                <span class="text-[10px] text-muted italic">No groups</span>
                @endforelse
                @if($member->groups->count() > 2)
                <span class="px-2 py-0.5 rounded-md bg-amber-500/10 text-amber-600 text-[9px] font-bold uppercase">+{{ $member->groups->count() - 2 }}</span>
                @endif
              </div>
            </td>
            <td class="px-6 py-4">
              <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full {{ $member->is_active ? 'bg-green-500/10 text-green-600' : 'bg-red-500/10 text-red-600' }} text-[10px] font-black uppercase tracking-wider">
                <span class="w-1.5 h-1.5 rounded-full {{ $member->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                {{ $member->is_active ? 'Active' : 'Inactive' }}
              </span>
            </td>
            <td class="px-6 py-4 text-right">
              <div class="flex items-center justify-end gap-1">
                <a href="{{ route('members.show', $member->id) }}" class="p-2 rounded-lg text-muted hover:text-primary hover:bg-primary/10 transition-all" title="View Profile">
                  <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </a>
                @if(auth()->user()->hasPermission('members.edit'))
                <a href="{{ route('members.edit', $member->id) }}" class="p-2 rounded-lg text-muted hover:text-blue-600 hover:bg-blue-500/10 transition-all" title="Edit Member">
                  <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </a>
                @endif
                @if(auth()->user()->hasPermission('members.delete'))
                <button class="p-2 rounded-lg text-muted hover:text-red-600 hover:bg-red-500/10 transition-all" onclick="deleteMember({{ $member->id }})" title="Delete Member">
                  <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
                @endif
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="px-6 py-12 text-center">
              <div class="flex flex-col items-center gap-2 opacity-50">
                <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <p class="text-sm font-medium">No members found matching your criteria.</p>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    
    <!-- PAGINATION -->
    <div class="card-footer bg-muted/5 px-6 py-4">
      {{ $members->appends(request()->query())->links() }}
    </div>
  </div>
</div>

<!-- IMPORT MODAL -->
<div class="modal-overlay" id="importModal">
  <div class="modal max-w-lg">
    <div class="modal-header border-b border-muted/10 p-6">
      <div>
        <div class="card-title text-lg">Import Members</div>
        <div class="card-subtitle text-xs">Bulk upload members from CSV file</div>
      </div>
      <div class="modal-close p-2 hover:bg-muted/10 rounded-lg transition-all cursor-pointer" onclick="closeModal('importModal')">✕</div>
    </div>
    <div class="modal-body p-6 space-y-6">
      <div class="p-8 border-2 border-dashed border-muted/20 rounded-2xl text-center hover:border-primary/50 hover:bg-primary/5 transition-all cursor-pointer" onclick="document.getElementById('importFile').click()">
        <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" class="mx-auto mb-4 text-muted">
          <path d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
        </svg>
        <p class="text-sm font-bold">Select CSV/Excel File</p>
        <p class="text-xs text-muted mt-1">Maximum file size: 10MB</p>
        <input type="file" id="importFile" accept=".csv,.xlsx,.xls" class="hidden">
      </div>
      
      <div class="bg-primary/5 p-4 rounded-xl space-y-3">
        <h4 class="text-[10px] font-black uppercase tracking-widest text-primary">Column Requirements</h4>
        <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-[10px] text-muted font-bold">
          <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-primary"></span> full_name *</div>
          <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-primary"></span> member_type *</div>
          <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-primary"></span> date_of_birth *</div>
          <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-primary"></span> address *</div>
          <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-muted/40"></span> email</div>
          <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-muted/40"></span> phone</div>
        </div>
      </div>
    </div>
    <div class="modal-footer p-6 border-t border-muted/10 flex gap-3">
      <button class="btn btn-ghost flex-1" onclick="closeModal('importModal')">Cancel</button>
      <button class="btn btn-primary flex-1" onclick="importMembers()">Start Import</button>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
function deleteMember(memberId) {
  if (confirm('Are you sure you want to delete this member? This action cannot be undone.')) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/members/${memberId}`;
    form.innerHTML = `
      @csrf
      @method('DELETE')
    `;
    document.body.appendChild(form);
    form.submit();
  }
}

function showImportModal() {
  document.getElementById('importModal').classList.add('open');
}

function closeModal(id) {
  document.getElementById(id).classList.remove('open');
}

function exportMembers() {
  window.location.href = '{{ route('members.template') }}';
}

function applyFilters() {
  const search = document.getElementById('searchInput').value;
  const type = document.getElementById('memberTypeFilter').value;
  const status = document.getElementById('statusFilter').value;
  
  const url = new URL(window.location.origin + window.location.pathname);
  if (search) url.searchParams.set('search', search);
  if (type) url.searchParams.set('type', type);
  if (status) url.searchParams.set('status', status);
  
  window.location.href = url.toString();
}

function resetFilters() {
  window.location.href = '{{ route('members.index') }}';
}

// Support enter key in search
document.getElementById('searchInput').addEventListener('keypress', function(e) {
  if (e.key === 'Enter') {
    applyFilters();
  }
});

function importMembers() {
  const fileInput = document.getElementById('importFile');
  if (!fileInput.files.length) {
    alert('Please select a file to import');
    return;
  }
  
  const form = document.getElementById('bulk-import-form');
  const fileInputHidden = document.getElementById('bulk-file');
  
  // Use the existing hidden form if it exists (from create page logic, but we can just use the same route)
  const formData = new FormData();
  formData.append('file', fileInput.files[0]);
  formData.append('_token', '{{ csrf_token() }}');

  fetch('{{ route('members.import') }}', {
    method: 'POST',
    body: formData
  })
  .then(response => {
    window.location.reload();
  })
  .catch(error => {
    alert('Error importing members');
    console.error(error);
  });
}
</script>
@endpush
