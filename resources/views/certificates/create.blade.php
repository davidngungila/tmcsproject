@extends('layouts.app')

@section('title', 'Generate Certificate - TmcsSmart')
@section('page-title', 'Generate New Certificate')
@section('breadcrumb', 'TmcsSmart / Certificates / Generate')

@section('content')
<div class="animate-in">
  <form action="{{ route('certificates.store') }}" method="POST">
    @csrf

    <div class="card mb-4">
      <div class="card-header">
        <div class="card-title">Certificate Details</div>
        <div class="card-subtitle">Information for the new certificate</div>
      </div>
      <div class="card-body">
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Member *</label>
            <select name="member_id" class="form-control" required>
              <option value="">Select Member</option>
              @foreach($members as $member)
              <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                {{ $member->full_name }} - {{ $member->registration_number }}
              </option>
              @endforeach
            </select>
            @error('member_id') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
          </div>
          <div class="form-group">
            <label class="form-label">Certificate Type *</label>
            <select name="certificate_type" class="form-control" required>
              <option value="">Select Type</option>
              <option value="Baptism" {{ old('certificate_type') == 'Baptism' ? 'selected' : '' }}>Baptism Certificate</option>
              <option value="Confirmation" {{ old('certificate_type') == 'Confirmation' ? 'selected' : '' }}>Confirmation Certificate</option>
              <option value="Marriage" {{ old('certificate_type') == 'Marriage' ? 'selected' : '' }}>Marriage Certificate</option>
              <option value="Membership" {{ old('certificate_type') == 'Membership' ? 'selected' : '' }}>Membership Certificate</option>
              <option value="Service" {{ old('certificate_type') == 'Service' ? 'selected' : '' }}>Service Recognition</option>
            </select>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Issue Date *</label>
            <input type="date" name="issue_date" class="form-control" value="{{ old('issue_date', now()->format('Y-m-d')) }}" required>
            @error('issue_date') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
          </div>
          <div class="form-group">
            <label class="form-label">Expiry Date (Optional)</label>
            <input type="date" name="expiry_date" class="form-control" value="{{ old('expiry_date') }}">
          </div>
        </div>
      </div>
    </div>

    <div class="flex gap-3">
      <a href="{{ route('certificates.index') }}" class="btn btn-secondary">Cancel</a>
      <button type="submit" class="btn btn-primary">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Generate Certificate
      </button>
    </div>
  </form>
</div>
@endsection
