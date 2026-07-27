@extends('layouts.app')

@section('title', 'Edit Event - TmcsSmart')
@section('page-title', 'Edit Event')
@section('breadcrumb', 'TmcsSmart / Events / Edit')

@section('content')
<div class="animate-in">
  <div class="card">
    <div class="card-header">
      <div class="card-title">Edit Event</div>
      <div class="card-subtitle">Update event information</div>
    </div>
    <div class="card-body">
      <form action="{{ route('events.update', $event->id) }}" method="POST">
        @method('PUT')
        @csrf
        
        <div class="form-group mb-4">
          <label class="form-label">Event Title *</label>
          <input type="text" name="title" class="form-control" value="{{ old('title', $event->event_name) }}" placeholder="Event title" required>
          @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="form-group mb-4">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" rows="4" placeholder="Event description...">{{ old('description', $event->description) }}</textarea>
          @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="form-group mb-4">
          <label class="form-label">Location *</label>
          <input type="text" name="location" class="form-control" value="{{ old('location', $event->venue) }}" placeholder="Event location" required>
          @error('location') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
          <div class="form-group">
            <label class="form-label">Start Date *</label>
            <input type="datetime-local" name="start_date" class="form-control" value="{{ old('start_date', $event->event_date->format('Y-m-d\TH:i')) }}" required>
            @error('start_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
          </div>
          <div class="form-group">
            <label class="form-label">End Date *</label>
            <input type="datetime-local" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
            @error('end_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
          </div>
        </div>

        <div class="form-group mb-4">
          <label class="form-label">Event Type</label>
          <input type="text" name="event_type" class="form-control" value="{{ old('event_type') }}" placeholder="e.g., Service, Meeting, Conference">
          @error('event_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-3 pt-4">
          <a href="{{ route('events.show', $event->id) }}" class="btn btn-secondary flex-1 text-center">Cancel</a>
          <button type="submit" class="btn btn-primary flex-1">
            Update Event
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
