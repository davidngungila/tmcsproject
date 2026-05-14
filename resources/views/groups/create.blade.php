@extends('layouts.app')

@section('title', 'Create Group - TmcsSmart')
@section('page-title', 'Create New Group')
@section('breadcrumb', 'TmcsSmart / Groups / Create')

@section('content')
<div class="animate-in">
  <form action="{{ route('groups.store') }}" method="POST">
    @csrf

    <div class="card mb-4">
      <div class="card-header">
        <div class="card-title">Group Details</div>
        <div class="card-subtitle">Basic information about the group</div>
      </div>
      <div class="card-body">
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Group Name *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            @error('name') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
          </div>
          <div class="form-group">
            <label class="form-label">Group Type *</label>
            <select name="type" class="form-control" required>
              <option value="">Select Type</option>
              <option value="Fellowship" {{ old('type') == 'Fellowship' ? 'selected' : '' }}>Fellowship</option>
              <option value="Community" {{ old('type') == 'Community' ? 'selected' : '' }}>Small Christian Community</option>
              <option value="Ministry" {{ old('type') == 'Ministry' ? 'selected' : '' }}>Ministry/Department</option>
              <option value="Choir" {{ old('type') == 'Choir' ? 'selected' : '' }}>Choir</option>
            </select>
            @error('type') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
          @error('description') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Meeting Day</label>
            <select name="meeting_day" class="form-control">
              <option value="">Select Day</option>
              @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
              <option value="{{ $day }}" {{ old('meeting_day') == $day ? 'selected' : '' }}>{{ $day }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Meeting Time</label>
            <input type="time" name="meeting_time" class="form-control" value="{{ old('meeting_time') }}">
          </div>
        </div>
      </div>
    </div>

    <div class="flex gap-3">
      <a href="{{ route('groups.index') }}" class="btn btn-secondary">Cancel</a>
      <button type="submit" class="btn btn-primary">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
        Create Group
      </button>
    </div>
  </form>
</div>
@endsection
