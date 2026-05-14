@extends('layouts.app')

@section('title', 'Member Profile - TMCS Smart')
@section('page-title', 'Member Profile')
@section('breadcrumb', 'Home / Members / ' . $member->full_name)

@section('content')
<div class="animate-in">
  <!-- MEMBER DETAILS HEADER -->
  <div class="card mb-6 overflow-hidden">
    <div class="h-24 bg-gradient-to-r from-green-600 to-green-400"></div>
    <div class="card-body -mt-12">
      <div class="flex flex-col md:flex-row items-center md:items-end gap-6">
        <!-- MEMBER PHOTO -->
        <div class="relative">
          <div class="w-32 h-32 rounded-2xl bg-white p-1 shadow-xl overflow-hidden">
            @if($member->photo)
              <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->full_name }}" class="w-full h-full rounded-xl object-cover">
            @else
              <div class="w-full h-full rounded-xl bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white text-3xl font-black">
                {{ substr($member->full_name, 0, 2) }}
              </div>
            @endif
          </div>
          <div class="absolute -bottom-2 -right-2 w-8 h-8 rounded-full bg-green-500 border-4 border-white flex-center text-white" title="Active">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
          </div>
        </div>
        
        <!-- MEMBER INFO -->
        <div class="flex-1 text-center md:text-left">
          <div class="flex flex-col md:flex-row items-center gap-3 mb-3">
            <h1 class="text-3xl font-black text-gray-900">{{ $member->full_name }}</h1>
            <div class="flex gap-2">
              <span class="badge {{ $member->is_active ? 'green' : 'red' }} uppercase font-black text-[10px]">
                {{ $member->is_active ? 'Active' : 'Inactive' }}
              </span>
              <span class="badge blue uppercase font-black text-[10px]">{{ $member->member_type }}</span>
            </div>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-sm">
            <div>
              <div class="text-[10px] text-muted uppercase font-bold tracking-widest mb-0.5">Registration No</div>
              <div class="font-black text-blue-600">{{ $member->registration_number }}</div>
            </div>
            <div>
              <div class="text-[10px] text-muted uppercase font-bold tracking-widest mb-0.5">Member Since</div>
              <div class="font-bold">{{ $member->registration_date->format('M d, Y') }}</div>
            </div>
            <div>
              <div class="text-[10px] text-muted uppercase font-bold tracking-widest mb-0.5">Email Address</div>
              <div class="font-bold">{{ $member->email }}</div>
            </div>
            <div>
              <div class="text-[10px] text-muted uppercase font-bold tracking-widest mb-0.5">Phone Number</div>
              <div class="font-bold">{{ $member->phone }}</div>
            </div>
          </div>
        </div>
        
        <!-- ACTION BUTTONS -->
        <div class="flex gap-2 mt-6 md:mt-0">
          @if(auth()->user()->hasPermission('members.edit'))
          <a href="{{ route('members.edit', $member->id) }}" class="btn btn-secondary px-6">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="mr-2"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit Profile
          </a>
          @endif
        </div>
      </div>
    </div>
  </div>

    <!-- MEMBER INFORMATION SECTIONS -->
    <div class="grid grid-cols-1 gap-6 mb-6">
      <!-- QUICK ACTIONS ROW -->
      <div class="card overflow-hidden">
        <div class="card-header border-b bg-light/30">
          <div class="card-title text-sm font-bold uppercase tracking-wider text-muted">Quick Actions</div>
          <div class="card-subtitle text-[10px]">Common tasks</div>
        </div>
        <div class="card-body">
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <button class="btn btn-secondary w-full flex items-center justify-center gap-2" onclick="printMemberCard({{ $member->id }})">
              <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
              Print Card
            </button>
            <button class="btn btn-secondary w-full flex items-center justify-center gap-2" onclick="sendEmail({{ $member->id }})">
              <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
              Send Email
            </button>
            <button class="btn btn-secondary w-full flex items-center justify-center gap-2" onclick="generateCertificate({{ $member->id }})">
              <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              Certificate
            </button>
            <button class="btn btn-secondary w-full flex items-center justify-center gap-2" onclick="viewHistory({{ $member->id }})">
              <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              History
            </button>
          </div>
        </div>
      </div>
    </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- PERSONAL INFORMATION -->
    <div class="lg:col-span-2">
      <div class="card mb-6 h-full">
        <div class="card-header border-b">
          <div class="card-title text-sm font-bold uppercase tracking-wider text-muted">Personal Information</div>
          <div class="card-subtitle text-[10px]">Detailed member bio and contact records</div>
        </div>
        <div class="card-body">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-6">
              <div class="p-4 bg-light/50 rounded-2xl border border-gray-100">
                <label class="text-[10px] text-muted uppercase font-bold tracking-widest mb-1 block">Full Name</label>
                <div class="text-sm font-black">{{ $member->full_name }}</div>
              </div>
              <div class="p-4 bg-light/50 rounded-2xl border border-gray-100">
                <label class="text-[10px] text-muted uppercase font-bold tracking-widest mb-1 block">Baptismal Name</label>
                <div class="text-sm font-bold text-blue-600">{{ $member->baptismal_name ?? 'N/A' }}</div>
              </div>
              <div class="p-4 bg-light/50 rounded-2xl border border-gray-100">
                <label class="text-[10px] text-muted uppercase font-bold tracking-widest mb-1 block">Date of Birth</label>
                <div class="text-sm font-bold">{{ $member->date_of_birth ? $member->date_of_birth->format('Y-m-d H:i:s') : 'N/A' }}</div>
              </div>
              <div class="p-4 bg-light/50 rounded-2xl border border-gray-100">
                <label class="text-[10px] text-muted uppercase font-bold tracking-widest mb-1 block">Member Type</label>
                <div class="text-sm font-bold capitalize">{{ $member->member_type }}</div>
              </div>
            </div>
            <div class="space-y-6">
              <div class="p-4 bg-light/50 rounded-2xl border border-gray-100">
                <label class="text-[10px] text-muted uppercase font-bold tracking-widest mb-1 block">Email Address</label>
                <div class="text-sm font-bold">{{ $member->email }}</div>
              </div>
              <div class="p-4 bg-light/50 rounded-2xl border border-gray-100">
                <label class="text-[10px] text-muted uppercase font-bold tracking-widest mb-1 block">Phone Number</label>
                <div class="text-sm font-bold">{{ $member->phone }}</div>
              </div>
              <div class="p-4 bg-light/50 rounded-2xl border border-gray-100">
                <label class="text-[10px] text-muted uppercase font-bold tracking-widest mb-1 block">Residential Address</label>
                <div class="text-sm font-bold leading-relaxed">{{ $member->address }}</div>
              </div>
              <div class="p-4 bg-light/50 rounded-2xl border border-gray-100">
                <label class="text-[10px] text-muted uppercase font-bold tracking-widest mb-1 block">Registration Date</label>
                <div class="text-sm font-bold">{{ $member->registration_date->format('M d, Y') }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- QUICK ACTIONS & QR CODE -->
    <div class="space-y-6">
      <!-- QR CODE -->
      <div class="card overflow-hidden">
        <div class="card-header border-b bg-light/30">
          <div class="card-title text-sm font-bold uppercase tracking-wider text-muted">Member Identification</div>
          <div class="card-subtitle text-[10px]">Scan for full member details</div>
        </div>
        <div class="card-body text-center py-10">
          @php
            $qrContent = "MEMBER PROFILE\n";
            $qrContent .= "------------------\n";
            $qrContent .= "Name: " . $member->full_name . "\n";
            $qrContent .= "Reg No: " . $member->registration_number . "\n";
            $qrContent .= "Baptismal: " . ($member->baptismal_name ?? 'N/A') . "\n";
            $qrContent .= "DOB: " . ($member->date_of_birth ? $member->date_of_birth->format('M d, Y') : 'N/A') . "\n";
            $qrContent .= "Type: " . strtoupper($member->member_type) . "\n";
            $qrContent .= "Email: " . ($member->email ?? 'N/A') . "\n";
            $qrContent .= "Phone: " . ($member->phone ?? 'N/A') . "\n";
            $qrContent .= "Address: " . ($member->address ?? 'N/A') . "\n";
            $qrContent .= "Joined: " . $member->registration_date->format('M d, Y') . "\n";
            if($member->groups->count() > 0) {
              $qrContent .= "Groups: " . implode(', ', $member->groups->pluck('name')->toArray());
            }
          @endphp
          <div class="p-6 bg-white rounded-3xl border-2 border-dashed border-gray-200 mb-6 inline-block shadow-sm">
            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(180)->generate($qrContent) !!}
          </div>
          <div class="text-xs font-black text-gray-900 mb-1 uppercase">{{ $member->full_name }}</div>
          <div class="text-[10px] text-muted uppercase font-bold tracking-widest">{{ $member->registration_number }}</div>
        </div>
        <div class="card-footer bg-light/30 p-4 border-t text-center">
            <button class="btn btn-dark btn-sm w-full rounded-xl" onclick="window.print()">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="mr-2"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print Identity Card
            </button>
        </div>
      </div>

      <!-- GROUP MEMBERSHIPS -->
      <div class="card">
        <div class="card-header border-b">
          <div class="card-title text-sm font-bold uppercase tracking-wider text-muted">Group Memberships</div>
          <div class="card-subtitle text-[10px]">Church groups this member belongs to</div>
        </div>
        <div class="card-body p-0">
          @if($member->groups->count() > 0)
            <div class="divide-y divide-gray-50">
              @foreach($member->groups as $group)
              <div class="p-4 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-green-50 text-green-600 flex-center">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div class="text-xs font-bold">{{ $group->name }}</div>
              </div>
              @endforeach
            </div>
          @else
            <div class="text-muted text-center py-8 text-xs italic">No group memberships recorded.</div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
function printMemberCard(id) {
  window.print();
}

function sendEmail(id) {
  // Logic for sending email
  alert('Send email feature for member ' + id);
}

function generateCertificate(id) {
  // Logic for generating certificate
  window.location.href = "{{ route('certificates.create') }}?member_id=" + id;
}

function viewHistory(id) {
  // Logic for viewing history
  alert('View history for member ' + id);
}
</script>
@endpush
