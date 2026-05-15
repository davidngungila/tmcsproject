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
            <label class="form-label">Regular Contribution Amount</label>
            <div class="input-group">
              <span class="input-group-text">KES</span>
              <input type="number" step="0.01" name="regular_contribution_amount" class="form-control" value="{{ old('regular_contribution_amount', 0) }}">
            </div>
            <p class="text-xs text-muted mt-1">Default amount per member per meeting</p>
          </div>
        </div>

        <div class="border-t pt-4 mt-4">
          <h4 class="text-sm font-bold mb-4 uppercase text-muted">Leadership Assignment</h4>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="form-group">
              <label class="form-label">Chairperson</label>
              <select name="chairperson_id" class="form-control select2">
                <option value="">Select Chairperson</option>
                @foreach($members as $member)
                <option value="{{ $member->id }}" {{ old('chairperson_id') == $member->id ? 'selected' : '' }}>{{ $member->full_name }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Secretary</label>
              <select name="secretary_id" class="form-control select2">
                <option value="">Select Secretary</option>
                @foreach($members as $member)
                <option value="{{ $member->id }}" {{ old('secretary_id') == $member->id ? 'selected' : '' }}>{{ $member->full_name }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Accountant</label>
              <select name="accountant_id" class="form-control select2">
                <option value="">Select Accountant</option>
                @foreach($members as $member)
                <option value="{{ $member->id }}" {{ old('accountant_id') == $member->id ? 'selected' : '' }}>{{ $member->full_name }}</option>
                @endforeach
              </select>
            </div>
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
