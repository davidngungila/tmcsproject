@extends('layouts.app')

@section('title', 'My Profile - TmcsSmart')
@section('page-title', 'My Profile')
@section('breadcrumb', 'TmcsSmart / Member / Profile')

@section('content')
<div class="animate-in">
  <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    
    <!-- LEFT COLUMN: PHOTO & QUICK INFO -->
    <div class="lg:col-span-1 space-y-6">
      <div class="card text-center">
        <div class="card-body py-8">
          <div class="w-32 h-32 rounded-full border-4 border-light mx-auto mb-4 overflow-hidden bg-light flex-center">
            @if($member->photo)
              <img src="{{ asset('storage/' . $member->photo) }}" class="w-full h-full object-cover">
            @else
              <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" class="text-muted"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            @endif
          </div>
          <h3 class="font-bold text-lg mb-1">{{ $member->full_name }}</h3>
          <p class="text-xs text-muted mb-4">{{ $member->registration_number }}</p>
          <div class="badge {{ $member->is_active ? 'green' : 'red' }} mb-6">
            {{ $member->is_active ? 'Active Member' : 'Inactive' }}
          </div>
          <div class="flex flex-col gap-2">
            <a href="{{ route('member.profile.pay') }}" class="btn btn-primary w-full btn-sm">
              <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="mr-1.5"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              Make Payment
            </a>
            <a href="{{ route('member.profile.edit') }}" class="btn btn-secondary w-full btn-sm">Edit Profile</a>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header border-b">
          <div class="card-title text-sm">Quick Stats</div>
        </div>
        <div class="card-body p-0">
          <div class="divide-y divide-gray-50">
            <div class="p-4 flex justify-between items-center">
              <span class="text-xs text-muted uppercase font-bold tracking-wider">Type</span>
              <span class="text-sm font-medium capitalize">{{ $member->member_type }}</span>
            </div>
            <div class="p-4 flex justify-between items-center">
              <span class="text-xs text-muted uppercase font-bold tracking-wider">Join Date</span>
              <span class="text-sm font-medium">{{ $member->registration_date->format('M Y') }}</span>
            </div>
            <div class="p-4 flex justify-between items-center">
              <span class="text-xs text-muted uppercase font-bold tracking-wider">Total Contributions</span>
              <span class="text-sm font-medium">{{ number_format($member->contributions->sum('amount')) }} TZS</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- RIGHT COLUMN: DETAILS -->
    <div class="lg:col-span-3 space-y-6">
      
      <!-- PERSONAL DETAILS -->
      <div class="card">
        <div class="card-header border-b flex items-center justify-between">
          <div class="card-title">Detailed Information</div>
          <span class="text-[10px] text-muted">Last updated: {{ $member->updated_at->format('d M, Y H:i') }}</span>
        </div>
        <div class="card-body">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-6">
            <div class="p-3 bg-light/50 rounded-xl">
              <label class="text-[10px] text-muted uppercase font-bold tracking-wider mb-1 block">Registration Number</label>
              <div class="text-sm font-bold text-blue-600">{{ $member->registration_number }}</div>
            </div>
            <div class="p-3 bg-light/50 rounded-xl">
              <label class="text-[10px] text-muted uppercase font-bold tracking-wider mb-1 block">Full Name</label>
              <div class="text-sm font-medium">{{ $member->full_name }}</div>
            </div>
            <div class="p-3 bg-light/50 rounded-xl">
              <label class="text-[10px] text-muted uppercase font-bold tracking-wider mb-1 block">Baptismal Name</label>
              <div class="text-sm font-medium">{{ $member->baptismal_name ?? 'N/A' }}</div>
            </div>
            <div class="p-3 bg-light/50 rounded-xl">
              <label class="text-[10px] text-muted uppercase font-bold tracking-wider mb-1 block">Email Address</label>
              <div class="text-sm font-medium">{{ $member->email ?? 'Not provided' }}</div>
            </div>
            <div class="p-3 bg-light/50 rounded-xl">
              <label class="text-[10px] text-muted uppercase font-bold tracking-wider mb-1 block">Phone Number</label>
              <div class="text-sm font-medium">{{ $member->phone ?? 'Not provided' }}</div>
            </div>
            <div class="p-3 bg-light/50 rounded-xl">
              <label class="text-[10px] text-muted uppercase font-bold tracking-wider mb-1 block">Date of Birth</label>
              <div class="text-sm font-medium">{{ $member->date_of_birth->format('d M, Y') }}</div>
            </div>
            <div class="p-3 bg-light/50 rounded-xl">
              <label class="text-[10px] text-muted uppercase font-bold tracking-wider mb-1 block">Member Category</label>
              <div class="text-sm font-medium capitalize">{{ $member->member_type }}</div>
            </div>
            <div class="p-3 bg-light/50 rounded-xl">
              <label class="text-[10px] text-muted uppercase font-bold tracking-wider mb-1 block">Registration Date</label>
              <div class="text-sm font-medium">{{ $member->registration_date->format('d M, Y') }}</div>
            </div>
            <div class="p-3 bg-light/50 rounded-xl">
              <label class="text-[10px] text-muted uppercase font-bold tracking-wider mb-1 block">Address</label>
              <div class="text-sm font-medium">{{ $member->address }}</div>
            </div>
          </div>
        </div>
      </div>

      <!-- MY QR CODE -->
      <div class="card overflow-hidden">
        <div class="card-header border-b bg-light/30">
          <div class="card-title text-sm">Member Identity QR</div>
        </div>
        <div class="card-body flex flex-col items-center py-10">
          <div class="p-4 bg-white rounded-2xl border-2 border-dashed border-gray-200 mb-4">
            {!! QrCode::size(150)->generate($member->registration_number) !!}
          </div>
          <p class="text-xs text-muted max-w-xs text-center">Scan this QR code during church events for quick attendance check-in.</p>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection
