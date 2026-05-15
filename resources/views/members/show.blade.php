@extends('layouts.app')

@section('title', 'Member Profile - TMCS Smart')
@section('page-title', 'Member Profile')
@section('breadcrumb', 'Home / Members / ' . $member->full_name)

@section('content')
<div class="animate-in space-y-6">
  <!-- MEMBER DETAILS HEADER -->
  <div class="card mb-6 overflow-hidden border-none shadow-lg">
    <div class="h-32 bg-gradient-to-r from-primary/80 to-primary"></div>
    <div class="card-body -mt-16 relative">
      <div class="flex flex-col md:flex-row items-center md:items-end gap-6">
        <!-- MEMBER PHOTO -->
        <div class="relative">
          <div class="w-40 h-40 rounded-3xl bg-card p-1 shadow-2xl overflow-hidden border-4 border-card">
            @if($member->photo)
              <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->full_name }}" class="w-full h-full rounded-2xl object-cover">
            @else
              <div class="w-full h-full rounded-2xl bg-gradient-to-br from-primary/20 to-primary/40 flex items-center justify-center text-primary text-4xl font-black">
                {{ substr($member->full_name, 0, 2) }}
              </div>
            @endif
          </div>
          <div class="absolute -bottom-2 -right-2 w-10 h-10 rounded-full bg-green-500 border-4 border-card flex items-center justify-center text-white shadow-lg" title="Active">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
          </div>
        </div>
        
        <!-- MEMBER INFO -->
        <div class="flex-1 text-center md:text-left pb-2">
          <div class="flex flex-col md:flex-row items-center gap-4 mb-4">
            <h1 class="text-4xl font-black tracking-tight">{{ $member->full_name }}</h1>
            <div class="flex gap-2">
              <span class="px-3 py-1 rounded-full bg-primary/10 text-primary text-[10px] font-black uppercase tracking-wider">
                {{ $member->category ? $member->category->name : $member->member_type }}
              </span>
              <span class="px-3 py-1 rounded-full {{ $member->is_active ? 'bg-green-500/10 text-green-600' : 'bg-red-500/10 text-red-600' }} text-[10px] font-black uppercase tracking-wider">
                {{ $member->is_active ? 'Active' : 'Inactive' }}
              </span>
            </div>
          </div>
          
          <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-sm">
            <div>
              <div class="text-[10px] text-muted uppercase font-bold tracking-widest mb-1.5 opacity-70">Registration No</div>
              <div class="font-black text-primary mono tracking-tighter">{{ $member->registration_number }}</div>
            </div>
            <div>
              <div class="text-[10px] text-muted uppercase font-bold tracking-widest mb-1.5 opacity-70">Member Since</div>
              <div class="font-bold">{{ $member->registration_date->format('M d, Y') }}</div>
            </div>
            <div>
              <div class="text-[10px] text-muted uppercase font-bold tracking-widest mb-1.5 opacity-70">Gender</div>
              <div class="font-bold">{{ $member->gender ?? 'Not Specified' }}</div>
            </div>
            <div>
              <div class="text-[10px] text-muted uppercase font-bold tracking-widest mb-1.5 opacity-70">Status</div>
              <div class="font-bold flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full {{ $member->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                {{ $member->is_active ? 'Active' : 'Inactive' }}
              </div>
            </div>
          </div>
        </div>
        
        <!-- ACTION BUTTONS -->
        <div class="flex gap-3 mb-2">
          @if(auth()->user()->hasPermission('members.edit'))
          <a href="{{ route('members.edit', $member->id) }}" class="btn btn-primary px-8 shadow-lg shadow-primary/20">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="mr-2"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit Profile
          </a>
          @endif
        </div>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- MAIN CONTENT -->
    <div class="lg:col-span-2 space-y-8">
      <!-- DETAILED INFORMATION -->
      <div class="card shadow-sm">
        <div class="card-header border-b border-muted/10 bg-muted/5 flex items-center justify-between">
          <div>
            <div class="card-title text-sm font-bold uppercase tracking-wider">Detailed Information</div>
            <div class="card-subtitle text-[10px]">Complete profile and contact records</div>
          </div>
          <svg width="20" height="20" class="text-primary opacity-50" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="card-body p-8">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
            <!-- PERSONAL -->
            <div class="space-y-6">
              <h4 class="text-xs font-black uppercase tracking-widest text-primary/60 border-b border-primary/10 pb-2">Personal Details</h4>
              <div class="flex flex-col gap-1">
                <label class="text-[10px] text-muted uppercase font-bold tracking-widest">Baptismal Name</label>
                <div class="text-sm font-bold">{{ $member->baptismal_name ?? 'N/A' }}</div>
              </div>
              <div class="flex flex-col gap-1">
                <label class="text-[10px] text-muted uppercase font-bold tracking-widest">Date of Birth</label>
                <div class="text-sm font-bold">{{ $member->date_of_birth ? $member->date_of_birth->format('F d, Y') : 'N/A' }}</div>
              </div>
              <div class="flex flex-col gap-1">
                <label class="text-[10px] text-muted uppercase font-bold tracking-widest">Gender</label>
                <div class="text-sm font-bold">{{ $member->gender ?? 'Not Specified' }}</div>
              </div>
              <div class="flex flex-col gap-1">
                <label class="text-[10px] text-muted uppercase font-bold tracking-widest">Category</label>
                <div class="text-sm font-bold">{{ $member->category ? $member->category->name : 'N/A' }}</div>
              </div>
            </div>

            <!-- CONTACT & LOCATION -->
            <div class="space-y-6">
              <h4 class="text-xs font-black uppercase tracking-widest text-primary/60 border-b border-primary/10 pb-2">Contact & Location</h4>
              <div class="flex flex-col gap-1">
                <label class="text-[10px] text-muted uppercase font-bold tracking-widest">Email Address</label>
                <div class="text-sm font-bold">{{ $member->email ?? 'N/A' }}</div>
              </div>
              <div class="flex flex-col gap-1">
                <label class="text-[10px] text-muted uppercase font-bold tracking-widest">Phone Number</label>
                <div class="text-sm font-bold">{{ $member->phone ?? 'N/A' }}</div>
              </div>
              <div class="flex flex-col gap-1">
                <label class="text-[10px] text-muted uppercase font-bold tracking-widest">Residential Address</label>
                <div class="text-sm font-bold leading-relaxed">{{ $member->address }}</div>
              </div>
              <div class="grid grid-cols-3 gap-4">
                <div class="flex flex-col gap-1">
                  <label class="text-[10px] text-muted uppercase font-bold tracking-widest">Parish</label>
                  <div class="text-xs font-bold">{{ $member->parish ?? 'N/A' }}</div>
                </div>
                <div class="flex flex-col gap-1">
                  <label class="text-[10px] text-muted uppercase font-bold tracking-widest">Diocese</label>
                  <div class="text-xs font-bold">{{ $member->diocese ?? 'N/A' }}</div>
                </div>
                <div class="flex flex-col gap-1">
                  <label class="text-[10px] text-muted uppercase font-bold tracking-widest">Region</label>
                  <div class="text-xs font-bold">{{ $member->region ?? 'N/A' }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- RECENT ACTIVITY OR FINANCIALS COULD GO HERE -->
    </div>

    <!-- SIDEBAR -->
    <div class="space-y-8">
      <!-- QR IDENTIFICATION -->
      <div class="card overflow-hidden shadow-sm border-none bg-gradient-to-b from-card to-muted/5">
        <div class="card-header border-b border-muted/10 bg-muted/5">
          <div class="card-title text-sm font-bold uppercase tracking-wider">Member ID QR</div>
        </div>
        <div class="card-body text-center py-10">
          @php
            $qrContent = "MEMBER PROFILE\n";
            $qrContent .= "Name: " . $member->full_name . "\n";
            $qrContent .= "Reg No: " . $member->registration_number . "\n";
            $qrContent .= "Baptismal: " . ($member->baptismal_name ?? 'N/A') . "\n";
            $qrContent .= "Type: " . ($member->category ? $member->category->name : $member->member_type) . "\n";
            $qrContent .= "Email: " . ($member->email ?? 'N/A') . "\n";
            $qrContent .= "Phone: " . ($member->phone ?? 'N/A') . "\n";
          @endphp
          <div class="p-4 bg-white rounded-3xl border-2 border-dashed border-primary/20 mb-6 inline-block shadow-inner">
            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(160)->margin(1)->generate($qrContent) !!}
          </div>
          <div class="px-6">
            <div class="text-sm font-black tracking-tight mb-1">{{ $member->full_name }}</div>
            <div class="text-[10px] text-primary font-bold uppercase tracking-widest mono">{{ $member->registration_number }}</div>
          </div>
        </div>
        <div class="card-footer bg-muted/5 p-4 border-t border-muted/10">
            <a href="{{ route('members.id-card', $member->id) }}" class="btn btn-secondary w-full rounded-xl py-3 flex items-center justify-center gap-2">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print Identity Card
            </a>
        </div>
      </div>

      <!-- GROUPS -->
      <div class="card shadow-sm">
        <div class="card-header border-b border-muted/10 bg-muted/5 flex items-center justify-between">
          <div class="card-title text-sm font-bold uppercase tracking-wider">Group Memberships</div>
          <span class="px-2 py-0.5 rounded-full bg-primary/10 text-primary text-[10px] font-bold">{{ $member->groups->count() }}</span>
        </div>
        <div class="card-body p-2">
          @if($member->groups->count() > 0)
            <div class="space-y-1">
              @foreach($member->groups as $group)
              <div class="p-3 flex items-center gap-3 rounded-xl hover:bg-muted/5 transition-colors group">
                <div class="w-10 h-10 rounded-xl bg-primary/5 text-primary flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-all">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                  <div class="text-sm font-bold">{{ $group->name }}</div>
                  <div class="text-[10px] text-muted uppercase font-bold">Member</div>
                </div>
              </div>
              @endforeach
            </div>
          @else
            <div class="text-muted text-center py-12 text-xs italic opacity-60 flex flex-col items-center gap-2">
              <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
              No groups joined yet.
            </div>
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
  window.location.href = "{{ route('members.id-card', $member->id) }}";
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
