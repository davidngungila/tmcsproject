@extends('layouts.app')

@section('title', 'Edit Contribution Category - TmcsSmart')
@section('page-title', 'Edit: ' . $contributionType->name)
@section('breadcrumb', 'TmcsSmart / Finance / Types / Edit')

@section('content')
<div class="animate-in max-w-4xl mx-auto">
  <form action="{{ route('finance.types.update', $contributionType->id) }}" method="POST" class="space-y-6">
    @csrf
    @method('PUT')
    
    <div class="card shadow-sm border-muted/10">
      <div class="card-header border-b p-6 bg-muted/5">
        <h3 class="card-title text-lg font-bold">Category Modification</h3>
        <p class="text-xs text-muted mt-1">Update the configuration for this financial category.</p>
      </div>
      
      <div class="card-body p-6 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="form-group">
            <label class="text-[10px] font-black uppercase tracking-widest text-muted block mb-2">Category Code *</label>
            <input type="text" name="code" class="form-control" value="{{ old('code', $contributionType->code) }}" required>
            @error('code') <p class="text-red text-[10px] mt-1">{{ $message }}</p> @enderror
          </div>

          <div class="form-group">
            <label class="text-[10px] font-black uppercase tracking-widest text-muted block mb-2">Category Name *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $contributionType->name) }}" required>
            @error('name') <p class="text-red text-[10px] mt-1">{{ $message }}</p> @enderror
          </div>
        </div>

        <div class="form-group">
          <label class="text-[10px] font-black uppercase tracking-widest text-muted block mb-2">Description</label>
          <textarea name="description" class="form-control" rows="3">{{ old('description', $contributionType->description) }}</textarea>
          @error('description') <p class="text-red text-[10px] mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="form-group">
            <label class="text-[10px] font-black uppercase tracking-widest text-muted block mb-2">GL Account No.</label>
            <input type="text" name="gl_account" class="form-control" value="{{ old('gl_account', $contributionType->gl_account) }}">
          </div>

          <div class="form-group">
            <label class="text-[10px] font-black uppercase tracking-widest text-muted block mb-2">Min. Amount (TZS)</label>
            <input type="number" name="min_amount" class="form-control" value="{{ old('min_amount', $contributionType->min_amount) }}" min="0">
          </div>

          <div class="form-group">
            <label class="text-[10px] font-black uppercase tracking-widest text-muted block mb-2">Frequency</label>
            <select name="frequency" class="form-control">
              <option value="one-time" {{ old('frequency', $contributionType->frequency) == 'one-time' ? 'selected' : '' }}>One-time / Occasional</option>
              <option value="weekly" {{ old('frequency', $contributionType->frequency) == 'weekly' ? 'selected' : '' }}>Weekly</option>
              <option value="monthly" {{ old('frequency', $contributionType->frequency) == 'monthly' ? 'selected' : '' }}>Monthly</option>
              <option value="annual" {{ old('frequency', $contributionType->frequency) == 'annual' ? 'selected' : '' }}>Annual</option>
            </select>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4 border-t border-dashed">
          <div class="form-group">
            <label class="text-[10px] font-black uppercase tracking-widest text-muted block mb-2">Visual Color</label>
            <select name="color" class="form-control">
              @foreach(['blue', 'green', 'amber', 'red', 'purple', 'indigo'] as $color)
                <option value="{{ $color }}" {{ old('color', $contributionType->color) == $color ? 'selected' : '' }}>{{ ucfirst($color) }}</option>
              @endforeach
            </select>
          </div>

          <div class="flex items-center gap-4 mt-6">
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="checkbox" name="is_mandatory" value="1" {{ old('is_mandatory', $contributionType->is_mandatory) ? 'checked' : '' }}>
              <span class="text-xs font-bold text-primary">Mandatory Payment</span>
            </label>
          </div>

          <div class="form-group">
            <label class="text-[10px] font-black uppercase tracking-widest text-muted block mb-2">Active Status</label>
            <select name="is_active" class="form-control">
              <option value="1" {{ old('is_active', $contributionType->is_active) ? 'selected' : '' }}>Active</option>
              <option value="0" {{ !old('is_active', $contributionType->is_active) ? 'selected' : '' }}>Inactive</option>
            </select>
          </div>
        </div>
      </div>

      <div class="card-footer bg-muted/5 p-6 border-t flex items-center justify-end gap-3">
        <a href="{{ route('finance.types.index') }}" class="btn btn-secondary px-6 font-bold uppercase tracking-widest text-[10px]">Cancel</a>
        <button type="submit" class="btn btn-primary px-10 font-bold uppercase tracking-widest text-[10px]">Save Changes</button>
      </div>
    </div>
  </form>
</div>
@endsection
