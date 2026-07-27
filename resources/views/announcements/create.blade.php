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
      <form action="{{ route('announcements.store') }}" method="POST">
        @csrf
        
        <div class="form-group mb-4">
          <label class="form-label">Title *</label>
          <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="Announcement title" required>
          @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
          <div class="form-group">
            <label class="form-label">Type *</label>
            <select name="type" class="form-control" required>
              <option value="">Select Type</option>
              <option value="general" {{ old('type') == 'general' ? 'selected' : '' }}>General</option>
              <option value="event" {{ old('type') == 'event' ? 'selected' : '' }}>Event</option>
              <option value="urgent" {{ old('type') == 'urgent' ? 'selected' : '' }}>Urgent</option>
            </select>
            @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
          </div>
          <div class="form-group">
            <label class="form-label">Target Audience *</label>
            <select name="target_audience" class="form-control" required>
              <option value="">Select Audience</option>
              <option value="all" {{ old('target_audience') == 'all' ? 'selected' : '' }}>All Members</option>
              <option value="members" {{ old('target_audience') == 'members' ? 'selected' : '' }}>Members Only</option>
              <option value="staff" {{ old('target_audience') == 'staff' ? 'selected' : '' }}>Staff Only</option>
              <option value="leadership" {{ old('target_audience') == 'leadership' ? 'selected' : '' }}>Leadership Only</option>
            </select>
            @error('target_audience') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
          </div>
        </div>

        <div class="form-group mb-4">
          <label class="form-label">Content *</label>
          <textarea name="content" class="form-control" rows="6" placeholder="Announcement content..." required>{{ old('content') }}</textarea>
          @error('content') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
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
          <button type="submit" class="btn btn-primary flex-1">
            Create Announcement
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
