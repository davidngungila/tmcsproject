@extends('layouts.app')

@section('title', 'Edit Group - TmcsSmart')
@section('page-title', 'Edit Group: ' . $group->name)
@section('breadcrumb', 'TmcsSmart / Groups / Edit')

@section('content')
<div class="animate-in">
  <form action="{{ route('groups.update', $group->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="card mb-4">
      <div class="card-header">
        <div class="card-title">Group Details</div>
        <div class="card-subtitle">Update group information</div>
      </div>
      <div class="card-body">
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Group Name *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $group->name) }}" required>
            @error('name') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
          </div>
          <div class="form-group">
            <label class="form-label">Group Type *</label>
            <select name="type" class="form-control" required>
              <option value="">Select Type</option>
              <option value="Fellowship" {{ old('type', $group->type) == 'Fellowship' ? 'selected' : '' }}>Fellowship</option>
              <option value="Community" {{ old('type', $group->type) == 'Community' ? 'selected' : '' }}>Small Christian Community</option>
              <option value="Ministry" {{ old('type', $group->type) == 'Ministry' ? 'selected' : '' }}>Ministry/Department</option>
              <option value="Choir" {{ old('type', $group->type) == 'Choir' ? 'selected' : '' }}>Choir</option>
            </select>
            @error('type') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" rows="3">{{ old('description', $group->description) }}</textarea>
          @error('description') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Meeting Day</label>
            <select name="meeting_day" class="form-control">
              <option value="">Select Day</option>
              @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
              <option value="{{ $day }}" {{ old('meeting_day', $group->meeting_day) == $day ? 'selected' : '' }}>{{ $day }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Meeting Time</label>
            <input type="time" name="meeting_time" class="form-control" value="{{ old('meeting_time', $group->meeting_time) }}">
          </div>
        </div>

        <div class="form-group">
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="is_active" value="1" class="rounded" {{ old('is_active', $group->is_active) ? 'checked' : '' }}>
            <span>Group is Active</span>
          </label>
        </div>
      </div>
    </div>

    <div class="flex gap-3">
      <a href="{{ route('groups.index') }}" class="btn btn-secondary">Cancel</a>
      <button type="submit" class="btn btn-primary">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
        Update Group
      </button>
    </div>
  </form>
</div>
@endsection
