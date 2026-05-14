@extends('layouts.app')

@section('title', 'Member Details - TmcsSmart')
@section('page-title', 'Member Details')
@section('breadcrumb', 'TmcsSmart / Members / Member Details')

@section('content')
<div class="animate-in">
  <!-- MEMBER DETAILS HEADER -->
  <div class="card mb-6">
    <div class="card-body">
      <div class="flex items-center gap-6">
        <!-- MEMBER PHOTO -->
        <div class="member-photo">
          @if($member->photo)
            <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->full_name }}" class="w-24 h-24 rounded-full object-cover border-4 border-green-100">
          @else
            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white text-2xl font-bold border-4 border-green-100">
              {{ substr($member->full_name, 0, 2) }}
            </div>
          @endif
        </div>
        
        <!-- MEMBER INFO -->
        <div class="flex-1">
          <div class="flex items-center gap-3 mb-2">
            <h1 class="text-2xl font-bold">{{ $member->full_name }}</h1>
            <span class="badge {{ $member->is_active ? 'green' : 'red' }} text-sm">
              {{ $member->is_active ? 'Active' : 'Inactive' }}
            </span>
            <span class="badge blue text-sm">{{ $member->member_type }}</span>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
              <span class="text-muted">Registration No:</span>
              <span class="font-semibold ml-2">{{ $member->registration_number }}</span>
            </div>
            <div>
              <span class="text-muted">Member Since:</span>
              <span class="font-semibold ml-2">{{ $member->registration_date->format('M d, Y') }}</span>
            </div>
            <div>
              <span class="text-muted">Email:</span>
              <span class="font-semibold ml-2">{{ $member->email }}</span>
            </div>
            <div>
              <span class="text-muted">Phone:</span>
              <span class="font-semibold ml-2">{{ $member->phone }}</span>
            </div>
          </div>
        </div>
        
        <!-- ACTION BUTTONS -->
        <div class="flex gap-2">
          @if(auth()->user()->hasPermission('members.edit'))
          <a href="{{ route('members.edit', $member->id) }}" class="btn btn-secondary">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit
          </a>
          @endif
          @if(auth()->user()->hasPermission('members.delete'))
          <button class="btn btn-red" onclick="confirmDelete({{ $member->id }})">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            Delete
          </button>
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- MEMBER INFORMATION SECTIONS -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- PERSONAL INFORMATION -->
    <div class="lg:col-span-2">
      <div class="card mb-6">
        <div class="card-header">
          <div class="card-title">Personal Information</div>
          <div class="card-subtitle">Basic member details</div>
        </div>
        <div class="card-body">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
              <div>
                <label class="form-label">Full Name</label>
                <div class="form-control bg-gray-50">{{ $member->full_name }}</div>
              </div>
              <div>
                <label class="form-label">Baptismal Name</label>
                <div class="form-control bg-gray-50">{{ $member->baptismal_name }}</div>
              </div>
              <div>
                <label class="form-label">Date of Birth</label>
                <div class="form-control bg-gray-50">{{ $member->date_of_birth }}</div>
              </div>
              <div>
                <label class="form-label">Member Type</label>
                <div class="form-control bg-gray-50">{{ $member->member_type }}</div>
              </div>
            </div>
            <div class="space-y-4">
              <div>
                <label class="form-label">Email Address</label>
                <div class="form-control bg-gray-50">{{ $member->email }}</div>
              </div>
              <div>
                <label class="form-label">Phone Number</label>
                <div class="form-control bg-gray-50">{{ $member->phone }}</div>
              </div>
              <div>
                <label class="form-label">Address</label>
                <div class="form-control bg-gray-50">{{ $member->address }}</div>
              </div>
              <div>
                <label class="form-label">Registration Date</label>
                <div class="form-control bg-gray-50">{{ $member->registration_date->format('M d, Y') }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- GROUP MEMBERSHIPS -->
      <div class="card">
        <div class="card-header">
          <div class="card-title">Group Memberships</div>
          <div class="card-subtitle">Church groups this member belongs to</div>
        </div>
        <div class="card-body">
          @if($member->groups->count() > 0)
            <div class="flex flex-wrap gap-2">
              @foreach($member->groups as $group)
              <span class="badge green text-sm">{{ $group->name }}</span>
              @endforeach
            </div>
          @else
            <div class="text-muted text-center py-4">No group memberships</div>
          @endif
        </div>
      </div>
    </div>

    <!-- QUICK ACTIONS & QR CODE -->
    <div class="space-y-6">
      <!-- QR CODE -->
      <div class="card">
        <div class="card-header">
          <div class="card-title">QR Code</div>
          <div class="card-subtitle">Member identification</div>
        </div>
        <div class="card-body text-center">
          <div class="w-32 h-32 mx-auto bg-gray-100 rounded-lg flex items-center justify-center mb-4">
            <div class="text-gray-400 text-xs text-center">
              QR Code<br>{{ $member->qr_code }}
            </div>
          </div>
          <div class="text-sm text-muted">{{ $member->qr_code }}</div>
        </div>
      </div>

      <!-- QUICK ACTIONS -->
      <div class="card">
        <div class="card-header">
          <div class="card-title">Quick Actions</div>
          <div class="card-subtitle">Common tasks</div>
        </div>
        <div class="card-body space-y-3">
          <button class="btn btn-secondary w-full" onclick="printMemberCard({{ $member->id }})">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Print Member Card
          </button>
          <button class="btn btn-secondary w-full" onclick="sendEmail({{ $member->id }})">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            Send Email
          </button>
          <button class="btn btn-secondary w-full" onclick="generateCertificate({{ $member->id }})">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Generate Certificate
          </button>
          <button class="btn btn-secondary w-full" onclick="viewHistory({{ $member->id }})">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            View History
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- DELETE CONFIRMATION MODAL -->
<div class="modal-overlay" id="deleteModal">
  <div class="modal" style="width: 400px;">
    <div class="modal-header">
      <div><div class="card-title">Confirm Delete</div><div class="card-subtitle">This action cannot be undone</div></div>
      <div class="modal-close" onclick="closeModal('deleteModal')">✕</div>
    </div>
    <div class="modal-body">
      <p>Are you sure you want to delete <strong>{{ $member->full_name }}</strong>?</p>
      <div class="flex gap-3 mt-6">
        <button class="btn btn-secondary flex-1" onclick="closeModal('deleteModal')">Cancel</button>
        <form method="POST" action="{{ route('members.destroy', $member->id) }}" class="flex-1">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-red w-full">Delete Member</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(memberId) {
  document.getElementById('deleteModal').classList.add('open');
}

function closeModal(modalId) {
  document.getElementById(modalId).classList.remove('open');
}

function printMemberCard(memberId) {
  window.print();
}

function sendEmail(memberId) {
  showToast('Email functionality coming soon', 'info');
}

function generateCertificate(memberId) {
  window.location.href = '{{ route('certificates.create') }}';
}

function viewHistory(memberId) {
  showToast('Member history coming soon', 'info');
}
</script>
@endpush
