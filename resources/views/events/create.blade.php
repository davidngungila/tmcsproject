@extends('layouts.app')

@section('title', 'Plan Event - TmcsSmart')
@section('page-title', 'Plan New Event')
@section('breadcrumb', 'TmcsSmart / Events / Plan')

@section('content')
<div class="animate-in">
  <form action="{{ route('events.store') }}" method="POST">
    @csrf

    <div class="card mb-4">
      <div class="card-header">
        <div class="card-title">Event Details</div>
        <div class="card-subtitle">Basic event information</div>
      </div>
      <div class="card-body">
        <div class="form-group">
          <label class="form-label">Event Title *</label>
          <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
          @error('title') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
          @error('description') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Location *</label>
            <input type="text" name="location" class="form-control" value="{{ old('location') }}" required>
            @error('location') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
          </div>
          <div class="form-group">
            <label class="form-label">Event Type *</label>
            <select name="event_type" class="form-control" required>
              <option value="">Select Type</option>
              <option value="Service" {{ old('event_type') == 'Service' ? 'selected' : '' }}>Church Service</option>
              <option value="Meeting" {{ old('event_type') == 'Meeting' ? 'selected' : '' }}>Meeting</option>
              <option value="Fellowship" {{ old('event_type') == 'Fellowship' ? 'selected' : '' }}>Fellowship</option>
              <option value="Seminar" {{ old('event_type') == 'Seminar' ? 'selected' : '' }}>Seminar/Workshop</option>
              <option value="Other" {{ old('event_type') == 'Other' ? 'selected' : '' }}>Other</option>
            </select>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Start Date & Time *</label>
            <input type="datetime-local" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
            @error('start_date') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
          </div>
          <div class="form-group">
            <label class="form-label">End Date & Time *</label>
            <input type="datetime-local" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
            @error('end_date') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
          </div>
        </div>
      </div>
    </div>

    <div class="flex gap-3">
      <a href="{{ route('events.index') }}" class="btn btn-secondary">Cancel</a>
      <button type="submit" class="btn btn-primary">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        Plan Event
      </button>
    </div>
  </form>
</div>
@endsection
