@extends('layouts.app')

@section('title', 'Add Contribution Type - TmcsSmart')
@section('page-title', 'New Contribution Type')
@section('breadcrumb', 'TmcsSmart / Finance / Types / Create')

@section('content')
<div class="animate-in max-w-2xl mx-auto">
  <div class="card shadow-sm border-muted/10">
    <div class="card-header border-b p-6">
      <h3 class="card-title text-lg">Create Contribution Type</h3>
      <p class="text-sm text-muted mt-1">Define a new category for financial contributions.</p>
    </div>
    <div class="card-body p-6">
      <form action="{{ route('finance.types.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="form-group">
          <label class="form-label">Type Name *</label>
          <input type="text" name="name" class="form-control" placeholder="e.g. Tithe, Offering, Building Fund" value="{{ old('name') }}" required>
          @error('name') <p class="text-red text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" rows="3" placeholder="Optional details about this type...">{{ old('description') }}</textarea>
          @error('description') <p class="text-red text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
          <label class="form-label">Status</label>
          <select name="is_active" class="form-control">
            <option value="1" {{ old('is_active') === '1' ? 'selected' : '' }}>Active</option>
            <option value="0" {{ old('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
          </select>
          @error('is_active') <p class="text-red text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t">
          <a href="{{ route('finance.types.index') }}" class="btn btn-secondary px-6">Cancel</a>
          <button type="submit" class="btn btn-primary px-8">Create Type</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
