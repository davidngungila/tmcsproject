@extends('layouts.app')

@section('title', 'Create Announcement - TmcsSmart')
@section('page-title', 'Create New Announcement')
@section('breadcrumb', 'TmcsSmart / Announcements / Create')

@section('content')
<div class="animate-in">
  <div class="card">
    <div class="card-header">
      <div class="card-title">New Announcement</div>
      <div class="card-subtitle">Create a new church announcement</div>
    </div>
    <div class="card-body">
      <form action="{{ route('announcements.store') }}" method="POST" class="space-y-4" id="announcementForm">
        @csrf
        
        <div class="form-group">
          <label class="form-label">Title *</label>
          <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="Announcement title" required>
          @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="form-group">
            <label class="form-label">Type *</label>
            <select name="type" class="form-control" required>
              <option value="">Select Type</option>
              <option value="General" {{ old('type') == 'General' ? 'selected' : '' }}>General</option>
              <option value="Event" {{ old('type') == 'Event' ? 'selected' : '' }}>Event</option>
              <option value="Urgent" {{ old('type') == 'Urgent' ? 'selected' : '' }}>Urgent</option>
              <option value="Service" {{ old('type') == 'Service' ? 'selected' : '' }}>Service</option>
              <option value="Meeting" {{ old('type') == 'Meeting' ? 'selected' : '' }}>Meeting</option>
            </select>
            @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
          </div>
          <div class="form-group">
            <label class="form-label">Target Audience *</label>
            <select name="target_audience" class="form-control" required>
              <option value="">Select Audience</option>
              <option value="All" {{ old('target_audience') == 'All' ? 'selected' : '' }}>All Members</option>
              <option value="Members" {{ old('target_audience') == 'Members' ? 'selected' : '' }}>Members Only</option>
              <option value="Staff" {{ old('target_audience') == 'Staff' ? 'selected' : '' }}>Staff Only</option>
              <option value="Leaders" {{ old('target_audience') == 'Leaders' ? 'selected' : '' }}>Leaders Only</option>
              <option value="Students" {{ old('target_audience') == 'Students' ? 'selected' : '' }}>Students Only</option>
            </select>
            @error('target_audience') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Content *</label>
          <textarea name="content" class="form-control" rows="6" placeholder="Announcement content..." required>{{ old('content') }}</textarea>
          @error('content') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="form-group">
            <label class="form-label">Expiry Date</label>
            <input type="date" name="expiry_date" class="form-control" value="{{ old('expiry_date') }}">
            <p class="text-[10px] text-muted mt-1">Leave empty for no expiry</p>
            @error('expiry_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
          </div>
          <div class="form-group">
            <label class="form-label flex items-center gap-2 cursor-pointer">
              <input type="checkbox" name="is_active" class="rounded" {{ old('is_active', true) ? 'checked' : '' }}>
              <span class="text-sm">Active</span>
            </label>
            <p class="text-[10px] text-muted mt-1">Uncheck to hide this announcement</p>
          </div>
        </div>

        <div class="flex gap-3 pt-4">
          <a href="{{ route('announcements.index') }}" class="btn btn-secondary flex-1 text-center">Cancel</a>
          <button type="submit" class="btn btn-primary flex-1" id="submitBtn">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" id="submitIcon"><path d="M5 13l4 4L19 7"/></svg>
            <span id="submitText">Create Announcement</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
// Form submission with loading indicator
document.getElementById('announcementForm').addEventListener('submit', function(e) {
  const submitBtn = document.getElementById('submitBtn');
  const submitIcon = document.getElementById('submitIcon');
  const submitText = document.getElementById('submitText');
  
  // Disable button and show loading state
  submitBtn.disabled = true;
  submitIcon.innerHTML = '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>';
  submitIcon.classList.add('animate-spin');
  submitText.textContent = 'Processing...';
});

// Check for session messages and show SweetAlert2
document.addEventListener('DOMContentLoaded', function() {
  @if(session('success'))
    Swal.fire({
      title: 'Success!',
      text: '{{ session('success') }}',
      icon: 'success',
      timer: 3000,
      showConfirmButton: false
    });
  @endif
  
  @if(session('error'))
    Swal.fire({
      title: 'Error!',
      text: '{{ session('error') }}',
      icon: 'error',
      confirmButtonColor: '#059669'
    });
  @endif
});
</script>
@endpush
