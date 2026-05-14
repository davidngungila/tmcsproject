@extends('layouts.app')

@section('title', 'Create Election - TmcsSmart')
@section('page-title', 'Create New Election')
@section('breadcrumb', 'TmcsSmart / Elections / Create')

@section('content')
<div class="animate-in">
  <form action="{{ route('elections.store') }}" method="POST">
    @csrf

    <div class="card mb-4">
      <div class="card-header">
        <div class="card-title">Election Details</div>
        <div class="card-subtitle">Basic information for the election</div>
      </div>
      <div class="card-body">
        <div class="form-group">
          <label class="form-label">Election Title *</label>
          <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="e.g. TMCS MoCU Executive Council 2024/2025" required>
          @error('title') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
          @error('description') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Start Date *</label>
            <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
            @error('start_date') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
          </div>
          <div class="form-group">
            <label class="form-label">End Date *</label>
            <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
            @error('end_date') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Positions (Enter one per line) *</label>
          <textarea name="positions_raw" class="form-control" rows="4" placeholder="Chairperson&#10;Secretary&#10;Treasurer" required>{{ old('positions_raw') }}</textarea>
          <p class="text-xs text-muted mt-1">These will be converted to selectable positions for candidates.</p>
        </div>
      </div>
    </div>

    <div class="flex gap-3">
      <a href="{{ route('elections.index') }}" class="btn btn-secondary">Cancel</a>
      <button type="submit" class="btn btn-primary">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
        Create Election
      </button>
    </div>
  </form>
</div>
@endsection
