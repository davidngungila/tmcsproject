@extends('layouts.app')

@section('title', 'Add Member - TmcsSmart')
@section('page-title', 'Add New Member')
@section('breadcrumb', 'TmcsSmart / Members / Add New')

@section('content')
<div class="animate-in space-y-6 max-w-5xl mx-auto">
  <!-- PAGE HEADER -->
  <div class="flex items-center justify-between mb-6">
    <div>
      <h2 class="text-2xl font-bold tracking-tight">Member Registration</h2>
      <p class="text-muted mt-1">Register a new member to the system. All fields marked with * are required.</p>
    </div>
    <div class="flex gap-3">
      <a href="{{ route('members.index') }}" class="btn btn-secondary">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back to List
      </a>
    </div>
  </div>

  <!-- BULK IMPORT BANNER -->
  <div class="card border-none bg-primary/5 mb-8">
    <div class="card-body p-6">
      <div class="flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="flex gap-4 items-center">
          <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
          </div>
          <div>
            <h3 class="font-bold text-lg">Bulk Import</h3>
            <p class="text-sm text-muted">Have many members? Use our CSV import tool to save time.</p>
          </div>
        </div>
        <div class="flex gap-3">
          <a href="{{ route('members.template') }}" class="btn btn-ghost btn-sm text-primary">Download Template</a>
          <button type="button" onclick="document.getElementById('bulk-file').click()" class="btn btn-primary btn-sm px-6">Upload CSV</button>
          <form id="bulk-import-form" action="{{ route('members.import') }}" method="POST" enctype="multipart/form-data" class="hidden">
            @csrf
            <input type="file" id="bulk-file" name="file" accept=".csv" onchange="this.form.submit()">
          </form>
        </div>
      </div>
    </div>
  </div>

  <form action="{{ route('members.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- LEFT COLUMN: MAIN INFO -->
      <div class="lg:col-span-2 space-y-6">
        <!-- PERSONAL INFORMATION -->
        <div class="card shadow-sm border-muted/20">
          <div class="card-header border-b border-muted/10 bg-muted/5">
            <div class="flex items-center gap-2">
              <svg width="18" height="18" class="text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
              <h3 class="font-bold text-base">Personal Information</h3>
            </div>
          </div>
          <div class="card-body p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="form-group">
                <label class="form-label text-xs font-bold uppercase tracking-wider text-muted mb-1.5">Full Name *</label>
                <input type="text" name="full_name" class="form-control focus:ring-2 focus:ring-primary/20" value="{{ old('full_name') }}" placeholder="e.g. John Doe" required>
                @error('full_name') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
              </div>
              <div class="form-group">
                <label class="form-label text-xs font-bold uppercase tracking-wider text-muted mb-1.5">Baptismal Name</label>
                <input type="text" name="baptismal_name" class="form-control focus:ring-2 focus:ring-primary/20" value="{{ old('baptismal_name') }}" placeholder="Optional">
                @error('baptismal_name') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="form-group">
                <label class="form-label text-xs font-bold uppercase tracking-wider text-muted mb-1.5">Gender *</label>
                <select name="gender" class="form-control focus:ring-2 focus:ring-primary/20" required>
                  <option value="">Select Gender</option>
                  <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                  <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                  <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('gender') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
              </div>
              <div class="form-group">
                <label class="form-label text-xs font-bold uppercase tracking-wider text-muted mb-1.5">Date of Birth *</label>
                <input type="date" name="date_of_birth" class="form-control focus:ring-2 focus:ring-primary/20" value="{{ old('date_of_birth') }}" required>
                @error('date_of_birth') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="form-group">
                <label class="form-label text-xs font-bold uppercase tracking-wider text-muted mb-1.5">Email Address</label>
                <div class="relative">
                  <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-muted">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                  </span>
                  <input type="email" name="email" class="form-control pl-9 focus:ring-2 focus:ring-primary/20" value="{{ old('email') }}" placeholder="john@example.com">
                </div>
                @error('email') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
              </div>
              <div class="form-group">
                <label class="form-label text-xs font-bold uppercase tracking-wider text-muted mb-1.5">Phone Number</label>
                <div class="relative">
                  <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-muted">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                  </span>
                  <input type="tel" name="phone" class="form-control pl-9 focus:ring-2 focus:ring-primary/20" value="{{ old('phone') }}" placeholder="e.g. 0712345678">
                </div>
                @error('phone') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
              </div>
            </div>
          </div>
        </div>

        <!-- LOCATION & ADDRESS -->
        <div class="card shadow-sm border-muted/20">
          <div class="card-header border-b border-muted/10 bg-muted/5">
            <div class="flex items-center gap-2">
              <svg width="18" height="18" class="text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              <h3 class="font-bold text-base">Location Information</h3>
            </div>
          </div>
          <div class="card-body p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div class="form-group">
                <label class="form-label text-xs font-bold uppercase tracking-wider text-muted mb-1.5">Parish</label>
                <input type="text" name="parish" class="form-control focus:ring-2 focus:ring-primary/20" value="{{ old('parish') }}" placeholder="Enter Parish">
                @error('parish') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
              </div>
              <div class="form-group">
                <label class="form-label text-xs font-bold uppercase tracking-wider text-muted mb-1.5">Diocese</label>
                <input type="text" name="diocese" class="form-control focus:ring-2 focus:ring-primary/20" value="{{ old('diocese') }}" placeholder="Enter Diocese">
                @error('diocese') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
              </div>
              <div class="form-group">
                <label class="form-label text-xs font-bold uppercase tracking-wider text-muted mb-1.5">Region</label>
                <input type="text" name="region" class="form-control focus:ring-2 focus:ring-primary/20" value="{{ old('region') }}" placeholder="Enter Region">
                @error('region') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
              </div>
            </div>
            <div class="form-group">
              <label class="form-label text-xs font-bold uppercase tracking-wider text-muted mb-1.5">Current Address *</label>
              <textarea name="address" class="form-control focus:ring-2 focus:ring-primary/20" rows="3" placeholder="Street, City, State/Province..." required>{{ old('address') }}</textarea>
              @error('address') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
            </div>
          </div>
        </div>
      </div>

      <!-- RIGHT COLUMN: SYSTEM & MEDIA -->
      <div class="space-y-6">
        <!-- MEMBERSHIP DETAILS -->
        <div class="card shadow-sm border-muted/20">
          <div class="card-header border-b border-muted/10 bg-muted/5">
            <div class="flex items-center gap-2">
              <svg width="18" height="18" class="text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
              <h3 class="font-bold text-base">Membership</h3>
            </div>
          </div>
          <div class="card-body p-6 space-y-4">
            <div class="form-group">
              <label class="form-label text-xs font-bold uppercase tracking-wider text-muted mb-1.5">Category *</label>
              <select name="category_id" id="category_id" class="form-control focus:ring-2 focus:ring-primary/20" required onchange="toggleRegNumber()">
                <option value="">Select Category</option>
                @foreach($categories as $category)
                  <option value="{{ $category->id }}" data-name="{{ $category->name }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
              </select>
              @error('category_id') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="form-group" id="program_group">
              <label class="form-label text-xs font-bold uppercase tracking-wider text-muted mb-1.5">Academic Programme</label>
              <select name="program_id" id="program_id" class="form-control focus:ring-2 focus:ring-primary/20 select2">
                <option value="">Select Programme</option>
                @foreach($programs as $program)
                  <option value="{{ $program->id }}" {{ old('program_id') == $program->id ? 'selected' : '' }}>
                    [{{ $program->code }}] {{ $program->name }}
                  </option>
                @endforeach
              </select>
              @error('program_id') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="form-group" id="reg_number_group" style="display: none;">
              <label class="form-label text-xs font-bold uppercase tracking-wider text-muted mb-1.5">Registration Number *</label>
              <input type="text" name="registration_number" id="registration_number" class="form-control focus:ring-2 focus:ring-primary/20" value="{{ old('registration_number') }}" placeholder="REG/2024/001">
              @error('registration_number') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
              <label class="form-label text-xs font-bold uppercase tracking-wider text-muted mb-1.5">Registration Date *</label>
              <input type="date" name="registration_date" class="form-control focus:ring-2 focus:ring-primary/20" value="{{ old('registration_date', now()->format('Y-m-d')) }}" required>
              @error('registration_date') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
            </div>
          </div>
        </div>

        <!-- PHOTO -->
        <div class="card shadow-sm border-muted/20">
          <div class="card-header border-b border-muted/10 bg-muted/5">
            <div class="flex items-center gap-2">
              <svg width="18" height="18" class="text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
              <h3 class="font-bold text-base">Member Photo</h3>
            </div>
          </div>
          <div class="card-body p-6">
            <div class="upload-box group border-2 border-dashed border-muted/30 rounded-2xl p-6 text-center hover:border-primary/50 hover:bg-primary/5 transition-all cursor-pointer" onclick="document.getElementById('photo').click()">
              <div id="photo-preview-container">
                <svg width="40" height="40" class="mx-auto mb-3 text-muted group-hover:text-primary transition-colors" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <p class="text-sm font-semibold">Click to upload photo</p>
                <p class="text-xs text-muted mt-1">JPG, PNG or GIF (MAX. 2MB)</p>
              </div>
              <input type="file" id="photo" name="photo" accept="image/*" class="hidden">
            </div>
            @error('photo') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
          </div>
        </div>

        <!-- GROUPS -->
        <div class="card shadow-sm border-muted/20">
          <div class="card-header border-b border-muted/10 bg-muted/5">
            <div class="flex items-center gap-2">
              <svg width="18" height="18" class="text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
              <h3 class="font-bold text-base">Group Assignments</h3>
            </div>
          </div>
          <div class="card-body p-6">
            <div class="space-y-3 max-h-48 overflow-y-auto pr-2 custom-scrollbar">
              @foreach($groups as $group)
              <label class="flex items-center justify-between p-3 rounded-xl border border-muted/10 hover:border-primary/30 hover:bg-primary/5 transition-all cursor-pointer">
                <div class="flex items-center gap-3">
                  <input type="checkbox" name="groups[]" value="{{ $group->id }}" class="w-4 h-4 rounded border-muted/30 text-primary focus:ring-primary/20" {{ in_array($group->id, old('groups', [])) ? 'checked' : '' }}>
                  <span class="text-sm font-medium">{{ $group->name }}</span>
                </div>
                <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded-full bg-muted/10 text-muted">{{ $group->members->count() }} members</span>
              </label>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- FORM ACTIONS -->
    <div class="flex items-center justify-end gap-4 pt-6 border-t border-muted/10">
      <a href="{{ route('members.index') }}" class="btn btn-ghost px-8">Cancel</a>
      <button type="submit" class="btn btn-primary px-10 py-3 shadow-lg shadow-primary/20">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="mr-2"><path d="M5 13l4 4L19 7"/></svg>
        Register Member
      </button>
    </div>
  </form>
</div>
@endsection

@push('scripts')
<script>
// Toggle Registration Number based on category
function toggleRegNumber() {
  const categorySelect = document.getElementById('category_id');
  const regNumberGroup = document.getElementById('reg_number_group');
  const regNumberInput = document.getElementById('registration_number');
  const programGroup = document.getElementById('program_group');
  
  const selectedOption = categorySelect.options[categorySelect.selectedIndex];
  const categoryName = selectedOption ? selectedOption.getAttribute('data-name') : '';
  
  // Show Reg Number and Program for Undergraduate and Postgraduate
  const isStudent = ['Undergraduate', 'Postgraduate'].includes(categoryName);
  
  if (isStudent) {
    regNumberGroup.style.display = 'block';
    programGroup.style.display = 'block';
    regNumberInput.setAttribute('required', 'required');
  } else {
    regNumberGroup.style.display = 'none';
    programGroup.style.display = 'none';
    regNumberInput.removeAttribute('required');
  }
}

// Call on load to handle validation errors/old values
document.addEventListener('DOMContentLoaded', function() {
  toggleRegNumber();
});

// Photo preview
document.getElementById('photo').addEventListener('change', function(e) {
  const file = e.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function(e) {
      const container = document.getElementById('photo-preview-container');
      container.innerHTML = `
        <div class="relative inline-block">
          <img src="${e.target.result}" class="w-32 h-32 object-cover rounded-2xl shadow-md mx-auto mb-3">
          <button type="button" onclick="removePhoto(event)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow-lg hover:bg-red-600 transition-colors">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>
        <p class="text-sm font-semibold text-primary">Photo selected</p>
        <p class="text-xs text-muted mt-1">Click to change</p>
      `;
    };
    reader.readAsDataURL(file);
  }
});

function removePhoto(event) {
  event.stopPropagation();
  document.getElementById('photo').value = '';
  const container = document.getElementById('photo-preview-container');
  container.innerHTML = `
    <svg width="40" height="40" class="mx-auto mb-3 text-muted group-hover:text-primary transition-colors" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
    <p class="text-sm font-semibold">Click to upload photo</p>
    <p class="text-xs text-muted mt-1">JPG, PNG or GIF (MAX. 2MB)</p>
  `;
}
</script>

<style>
.custom-scrollbar::-webkit-scrollbar {
  width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: var(--muted);
  border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: var(--primary);
}
</style>
@endpush
