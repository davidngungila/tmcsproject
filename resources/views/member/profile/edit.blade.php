@extends('layouts.app')

@section('title', 'Edit Profile - TMCS Smart')
@section('page-title', 'Edit Profile')
@section('breadcrumb', 'Home / Member / Profile / Edit')

@section('content')
<div class="animate-in">
  <div class="card">
    <div class="card-header border-b">
      <div class="card-title">Update Your Information</div>
      <div class="card-subtitle">You can update specific details below. Other details must be updated by an administrator.</div>
    </div>
    <div class="card-body">
      <form action="{{ route('member.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="flex flex-col md:flex-row gap-8">
          <!-- Photo Section -->
          <div class="flex flex-col items-center md:w-1/3">
            <div class="w-48 h-48 rounded-full border-4 border-light mb-4 overflow-hidden bg-light flex-center group relative cursor-pointer" onclick="document.getElementById('photoInput').click()">
              @if($member->photo)
                <img src="{{ asset('storage/' . $member->photo) }}" class="w-full h-full object-cover">
              @else
                <svg width="64" height="64" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" class="text-muted"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
              @endif
              <div class="absolute inset-0 bg-black/40 flex-center opacity-0 group-hover:opacity-100 transition-opacity">
                <svg width="32" height="32" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              </div>
            </div>
            <input type="file" name="photo" id="photoInput" class="hidden" accept="image/*">
            <p class="text-sm text-muted text-center">Click to change profile photo</p>
            @error('photo') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
          </div>

          <!-- Form Fields -->
          <div class="flex-1 space-y-6">
            <!-- Personal Information -->
            <div>
              <h3 class="text-lg font-semibold mb-4 pb-2 border-b">Personal Information</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-group">
                  <label class="form-label">Full Name</label>
                  <input type="text" name="full_name" class="form-control" value="{{ old('full_name', $member->full_name) }}" placeholder="Your full name">
                  @error('full_name') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                  <label class="form-label">Email</label>
                  <input type="email" name="email" class="form-control" value="{{ old('email', $member->email) }}" placeholder="your@email.com">
                  @error('email') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                  <label class="form-label">Phone Number</label>
                  <input type="text" name="phone" class="form-control" value="{{ old('phone', $member->phone) }}" placeholder="e.g. 0622239304">
                  @error('phone') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                  <label class="form-label">Date of Birth</label>
                  <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $member->date_of_birth ? $member->date_of_birth->format('Y-m-d') : '') }}">
                  @error('date_of_birth') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                  <label class="form-label">Gender</label>
                  <select name="gender" class="form-control">
                    <option value="">Select Gender</option>
                    <option value="male" {{ old('gender', $member->gender) === 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender', $member->gender) === 'female' ? 'selected' : '' }}>Female</option>
                  </select>
                  @error('gender') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                  <label class="form-label">Baptismal Name</label>
                  <input type="text" name="baptismal_name" class="form-control" value="{{ old('baptismal_name', $member->baptismal_name) }}" placeholder="Your baptismal name">
                  @error('baptismal_name') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
                </div>
              </div>
            </div>

            <!-- Contact Information -->
            <div>
              <h3 class="text-lg font-semibold mb-4 pb-2 border-b">Contact Information</h3>
              <div class="grid grid-cols-1 gap-4">
                <div class="form-group">
                  <label class="form-label">Residential Address</label>
                  <textarea name="address" class="form-control" rows="3" placeholder="Enter your current residential address">{{ old('address', $member->address) }}</textarea>
                  @error('address') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
                </div>
              </div>
            </div>

            <!-- Church Information -->
            <div>
              <h3 class="text-lg font-semibold mb-4 pb-2 border-b">Church Information</h3>
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="form-group">
                  <label class="form-label">Region</label>
                  <input type="text" name="region" class="form-control" value="{{ old('region', $member->region) }}" placeholder="Region">
                  @error('region') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                  <label class="form-label">Diocese</label>
                  <input type="text" name="diocese" class="form-control" value="{{ old('diocese', $member->diocese) }}" placeholder="Diocese">
                  @error('diocese') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                  <label class="form-label">Parish</label>
                  <input type="text" name="parish" class="form-control" value="{{ old('parish', $member->parish) }}" placeholder="Parish">
                  @error('parish') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="flex gap-3 mt-8 pt-6 border-t">
          <a href="{{ route('member.profile.index') }}" class="btn btn-secondary flex-1 text-center">Cancel</a>
          <button type="submit" class="btn btn-primary flex-1">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
