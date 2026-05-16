@extends('layouts.app')

@section('title', 'Define Contribution Category - TmcsSmart')
@section('page-title', 'New Contribution Category')
@section('breadcrumb', 'TmcsSmart / Finance / Types / Create')

@section('content')
<div class="animate-in max-w-4xl mx-auto">
  <form action="{{ route('finance.types.store') }}" method="POST" class="space-y-6">
    @csrf
    
    <div class="card shadow-sm border-muted/10">
      <div class="card-header border-b p-6 bg-muted/5">
        <h3 class="card-title text-lg font-bold">Category Definition</h3>
        <p class="text-xs text-muted mt-1">Configure the core properties of this financial category.</p>
      </div>
      
      <div class="card-body p-6 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="form-group">
            <label class="text-[10px] font-black uppercase tracking-widest text-muted block mb-2">Category Code *</label>
            <input type="text" name="code" class="form-control" placeholder="e.g. TTH-01" value="{{ old('code') }}" required>
            <p class="text-[10px] text-muted mt-1">A unique identifier for internal tracking.</p>
            @error('code') <p class="text-red text-[10px] mt-1">{{ $message }}</p> @enderror
          </div>

          <div class="form-group">
            <label class="text-[10px] font-black uppercase tracking-widest text-muted block mb-2">Category Name *</label>
            <input type="text" name="name" class="form-control" placeholder="e.g. Monthly Tithe" value="{{ old('name') }}" required>
            <p class="text-[10px] text-muted mt-1">Display name shown to members and staff.</p>
            @error('name') <p class="text-red text-[10px] mt-1">{{ $message }}</p> @enderror
          </div>
        </div>

        <div class="form-group">
          <label class="text-[10px] font-black uppercase tracking-widest text-muted block mb-2">Description</label>
          <textarea name="description" class="form-control" rows="3" placeholder="Explain the purpose of this contribution...">{{ old('description') }}</textarea>
          @error('description') <p class="text-red text-[10px] mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="form-group">
            <label class="text-[10px] font-black uppercase tracking-widest text-muted block mb-2">GL Account No.</label>
            <input type="text" name="gl_account" class="form-control" placeholder="e.g. 4000-01" value="{{ old('gl_account') }}">
            @error('gl_account') <p class="text-red text-[10px] mt-1">{{ $message }}</p> @enderror
          </div>

          <div class="form-group">
            <label class="text-[10px] font-black uppercase tracking-widest text-muted block mb-2">Min. Amount (TZS)</label>
            <input type="number" name="min_amount" class="form-control" value="{{ old('min_amount', 0) }}" min="0">
            @error('min_amount') <p class="text-red text-[10px] mt-1">{{ $message }}</p> @enderror
          </div>

          <div class="form-group">
            <label class="text-[10px] font-black uppercase tracking-widest text-muted block mb-2">Frequency</label>
            <select name="frequency" class="form-control">
              <option value="one-time">One-time / Occasional</option>
              <option value="weekly">Weekly</option>
              <option value="monthly">Monthly</option>
              <option value="annual">Annual</option>
            </select>
            @error('frequency') <p class="text-red text-[10px] mt-1">{{ $message }}</p> @enderror
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4 border-t border-dashed">
          <div class="form-group">
            <label class="text-[10px] font-black uppercase tracking-widest text-muted block mb-2">Visual Color</label>
            <select name="color" class="form-control">
              <option value="blue">Blue</option>
              <option value="green">Green</option>
              <option value="amber">Amber</option>
              <option value="red">Red</option>
              <option value="purple">Purple</option>
              <option value="indigo">Indigo</option>
            </select>
          </div>

          <div class="flex items-center gap-4 mt-6">
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="checkbox" name="is_mandatory" value="1" {{ old('is_mandatory') ? 'checked' : '' }}>
              <span class="text-xs font-bold text-primary">Mandatory Payment</span>
            </label>
          </div>

          <div class="form-group">
            <label class="text-[10px] font-black uppercase tracking-widest text-muted block mb-2">Active Status</label>
            <select name="is_active" class="form-control">
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
        </div>
      </div>

      <div class="card-footer bg-muted/5 p-6 border-t flex items-center justify-end gap-3">
        <a href="{{ route('finance.types.index') }}" class="btn btn-secondary px-6 font-bold uppercase tracking-widest text-[10px]">Cancel</a>
        <button type="submit" class="btn btn-primary px-10 font-bold uppercase tracking-widest text-[10px]">Create Category</button>
      </div>
    </div>
  </form>
</div>
@endsection
