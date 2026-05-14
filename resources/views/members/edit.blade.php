@extends('layouts.app')

@section('title', 'Edit Member - TmcsSmart')
@section('page-title', 'Edit Member: ' . $member->full_name)
@section('breadcrumb', 'TmcsSmart / Members / Edit')

@section('content')
<div class="animate-in">
  <form action="{{ route('members.update', $member->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <!-- MEMBER INFORMATION -->
    <div class="card mb-4">
      <div class="card-header">
        <div class="card-title">Member Information</div>
        <div class="card-subtitle">Update basic member details</div>
      </div>
      <div class="card-body">
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Full Name *</label>
            <input type="text" name="full_name" class="form-control" value="{{ old('full_name', $member->full_name) }}" required>
            @error('full_name') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
          </div>
          <div class="form-group">
            <label class="form-label">Baptismal Name</label>
            <input type="text" name="baptismal_name" class="form-control" value="{{ old('baptismal_name', $member->baptismal_name) }}">
            @error('baptismal_name') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $member->email) }}">
            @error('email') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
          </div>
          <div class="form-group">
            <label class="form-label">Phone Number</label>
            <input type="tel" name="phone" class="form-control" value="{{ old('phone', $member->phone) }}">
            @error('phone') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Member Type *</label>
            <select name="member_type" class="form-control" required>
              <option value="">Select Type</option>
              <option value="student" {{ old('member_type', $member->member_type) == 'student' ? 'selected' : '' }}>Student</option>
              <option value="non-student" {{ old('member_type', $member->member_type) == 'non-student' ? 'selected' : '' }}>Non-Student</option>
              <option value="employee" {{ old('member_type', $member->member_type) == 'employee' ? 'selected' : '' }}>Employee</option>
              <option value="child" {{ old('member_type', $member->member_type) == 'child' ? 'selected' : '' }}>Child</option>
            </select>
            @error('member_type') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
          </div>
          <div class="form-group">
            <label class="form-label">Date of Birth *</label>
            <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $member->date_of_birth ? $member->date_of_birth->format('Y-m-d') : '') }}" required>
            @error('date_of_birth') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Address *</label>
          <textarea name="address" class="form-control" rows="3" required>{{ old('address', $member->address) }}</textarea>
          @error('address') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Status *</label>
                <select name="is_active" class="form-control" required>
                  <option value="1" {{ old('is_active', $member->is_active) ? 'selected' : '' }}>Active</option>
                  <option value="0" {{ !old('is_active', $member->is_active) ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('is_active') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Registration Date</label>
                <input type="date" class="form-control" value="{{ $member->registration_date ? $member->registration_date->format('Y-m-d') : '' }}" disabled>
                <span class="text-xs text-muted">Registration date cannot be changed.</span>
            </div>
        </div>
      </div>
    </div>

    <!-- PHOTO UPLOAD -->
    <div class="card mb-4">
      <div class="card-header">
        <div class="card-title">Member Photo</div>
        <div class="card-subtitle">Update member photo (optional)</div>
      </div>
      <div class="card-body">
        <div class="upload-box" onclick="document.getElementById('photo').click()">
          @if($member->photo)
          <img src="{{ asset('storage/' . $member->photo) }}" id="photo-preview" style="width: 120px; height: 120px; object-fit: cover; border-radius: 8px; margin: 0 auto 12px; display: block;">
          <p style="font-size:14px;font-weight:600;color:var(--text-secondary);">Click to change photo</p>
          @else
          <svg width="32" height="32" fill="none" stroke="var(--text-muted)" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 12px;display:block;">
            <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
          </svg>
          <p style="font-size:14px;font-weight:600;color:var(--text-secondary);">Click to upload photo</p>
          @endif
          <p style="font-size:12px;color:var(--text-muted);margin-top:4px;">JPG, PNG or GIF (MAX. 2MB)</p>
          <input type="file" id="photo" name="photo" accept="image/*" style="display:none;">
        </div>
        @error('photo') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
      </div>
    </div>

    <!-- GROUP ASSIGNMENT -->
    <div class="card mb-4">
      <div class="card-header">
        <div class="card-title">Group Assignment</div>
        <div class="card-subtitle">Manage member groups</div>
      </div>
      <div class="card-body">
        <div class="form-group">
          <label class="form-label">Select Groups</label>
          <div class="space-y-2">
            @foreach($groups as $group)
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="checkbox" name="groups[]" value="{{ $group->id }}" class="rounded" {{ in_array($group->id, old('groups', $memberGroups)) ? 'checked' : '' }}>
              <span>{{ $group->name }}</span>
              <span class="text-xs text-muted">({{ $group->members->count() }} members)</span>
            </label>
            @endforeach
          </div>
        </div>
      </div>
    </div>

    <!-- FORM ACTIONS -->
    <div class="flex gap-3">
      <a href="{{ route('members.index') }}" class="btn btn-secondary">Cancel</a>
      <button type="submit" class="btn btn-primary">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
        Update Member
      </button>
    </div>
  </form>
</div>
@endsection

@push('scripts')
<script>
// Photo preview
document.getElementById('photo').addEventListener('change', function(e) {
  const file = e.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function(e) {
      const uploadBox = document.querySelector('.upload-box');
      uploadBox.innerHTML = `
        <img src="${e.target.result}" style="width: 120px; height: 120px; object-fit: cover; border-radius: 8px; margin: 0 auto 12px; display: block;">
        <p style="font-size:14px;font-weight:600;color:var(--text-secondary);">Photo updated</p>
        <button type="button" onclick="removePhoto()" class="btn btn-secondary btn-sm mt-2">Remove Photo</button>
        <input type="file" id="photo" name="photo" accept="image/*" style="display:none;">
      `;
    };
    reader.readAsDataURL(file);
  }
});

function removePhoto() {
  document.getElementById('photo').value = '';
  const uploadBox = document.querySelector('.upload-box');
  uploadBox.innerHTML = `
    <svg width="32" height="32" fill="none" stroke="var(--text-muted)" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 12px;display:block;">
      <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
    </svg>
    <p style="font-size:14px;font-weight:600;color:var(--text-secondary);">Click to upload photo</p>
    <p style="font-size:12px;color:var(--text-muted);margin-top:4px;">JPG, PNG or GIF (MAX. 2MB)</p>
    <input type="file" id="photo" name="photo" accept="image/*" style="display:none;">
  `;
}
</script>
@endpush
