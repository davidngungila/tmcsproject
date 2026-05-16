@extends('layouts.app')

@section('title', 'Edit Contribution Type - TmcsSmart')
@section('page-title', 'Edit: ' . $contributionType->name)
@section('breadcrumb', 'TmcsSmart / Finance / Types / Edit')

@section('content')
<div class="animate-in max-w-2xl mx-auto">
  <div class="card shadow-sm border-muted/10">
    <div class="card-header border-b p-6">
      <h3 class="card-title text-lg">Update Contribution Type</h3>
      <p class="text-sm text-muted mt-1">Modify existing contribution category details.</p>
    </div>
    <div class="card-body p-6">
      <form action="{{ route('finance.types.update', $contributionType->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="form-group">
          <label class="form-label">Type Name *</label>
          <input type="text" name="name" class="form-control" placeholder="e.g. Tithe, Offering" value="{{ old('name', $contributionType->name) }}" required>
          @error('name') <p class="text-red text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" rows="3" placeholder="Optional details...">{{ old('description', $contributionType->description) }}</textarea>
          @error('description') <p class="text-red text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
          <label class="form-label">Status</label>
          <select name="is_active" class="form-control">
            <option value="1" {{ old('is_active', $contributionType->is_active) ? 'selected' : '' }}>Active</option>
            <option value="0" {{ !old('is_active', $contributionType->is_active) ? 'selected' : '' }}>Inactive</option>
          </select>
          @error('is_active') <p class="text-red text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t">
          <a href="{{ route('finance.types.index') }}" class="btn btn-secondary px-6">Cancel</a>
          <button type="submit" class="btn btn-primary px-8">Update Type</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
