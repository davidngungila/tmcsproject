@extends('layouts.app')

@section('title', 'Add Asset - TmcsSmart')
@section('page-title', 'Add New Church Asset')
@section('breadcrumb', 'TmcsSmart / Assets / Add')

@section('content')
<div class="animate-in">
  <form action="{{ route('assets.store') }}" method="POST">
    @csrf

    <div class="card mb-4">
      <div class="card-header">
        <div class="card-title">Asset Details</div>
        <div class="card-subtitle">Information about the church asset</div>
      </div>
      <div class="card-body">
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Asset Name *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            @error('name') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
          </div>
          <div class="form-group">
            <label class="form-label">Serial Number</label>
            <input type="text" name="serial_number" class="form-control" value="{{ old('serial_number') }}">
            @error('serial_number') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Category *</label>
            <select name="category" class="form-control" required>
              <option value="">Select Category</option>
              <option value="Furniture" {{ old('category') == 'Furniture' ? 'selected' : '' }}>Furniture</option>
              <option value="Electronics" {{ old('category') == 'Electronics' ? 'selected' : '' }}>Electronics</option>
              <option value="Musical Instruments" {{ old('category') == 'Musical Instruments' ? 'selected' : '' }}>Musical Instruments</option>
              <option value="Land & Buildings" {{ old('category') == 'Land & Buildings' ? 'selected' : '' }}>Land & Buildings</option>
              <option value="Vehicles" {{ old('category') == 'Vehicles' ? 'selected' : '' }}>Vehicles</option>
              <option value="Other" {{ old('category') == 'Other' ? 'selected' : '' }}>Other</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Status *</label>
            <select name="status" class="form-control" required>
              <option value="Available" {{ old('status') == 'Available' ? 'selected' : '' }}>Available</option>
              <option value="Assigned" {{ old('status') == 'Assigned' ? 'selected' : '' }}>Assigned</option>
              <option value="Under Maintenance" {{ old('status') == 'Under Maintenance' ? 'selected' : '' }}>Under Maintenance</option>
              <option value="Retired" {{ old('status') == 'Retired' ? 'selected' : '' }}>Retired</option>
            </select>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Purchase Date</label>
            <input type="date" name="purchase_date" class="form-control" value="{{ old('purchase_date') }}">
          </div>
          <div class="form-group">
            <label class="form-label">Purchase Cost (TZS)</label>
            <input type="number" name="purchase_cost" class="form-control" step="0.01" value="{{ old('purchase_cost') }}">
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Location</label>
          <input type="text" name="location" class="form-control" value="{{ old('location') }}" placeholder="e.g. Main Sanctuary, Office 101">
        </div>
      </div>
    </div>

    <div class="flex gap-3">
      <a href="{{ route('assets.index') }}" class="btn btn-secondary">Cancel</a>
      <button type="submit" class="btn btn-primary">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        Add Asset
      </button>
    </div>
  </form>
</div>
@endsection
