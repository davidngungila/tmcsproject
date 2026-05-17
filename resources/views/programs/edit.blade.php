@extends('layouts.app')

@section('title', 'Edit Programme - TmcsSmart')
@section('page-title', 'Edit Programme: ' . $program->code)
@section('breadcrumb', 'TmcsSmart / Members / Programmes / Edit')

@section('content')
<div class="animate-in max-w-3xl mx-auto">
  <div class="card shadow-sm border-muted/10 overflow-hidden">
    <div class="card-header border-b border-muted/10 bg-muted/5">
      <div class="flex items-center gap-2">
        <svg width="18" height="18" class="text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        <h3 class="font-bold text-base">Update Programme Details</h3>
      </div>
    </div>
    <div class="card-body p-6">
      <form action="{{ route('programs.update', $program->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="form-group">
            <label class="form-label text-xs font-bold uppercase tracking-wider text-muted mb-1.5">Programme Name *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $program->name) }}" required>
            @error('name') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
          </div>
          <div class="form-group">
            <label class="form-label text-xs font-bold uppercase tracking-wider text-muted mb-1.5">Programme Code *</label>
            <input type="text" name="code" class="form-control" value="{{ old('code', $program->code) }}" required>
            @error('code') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="form-group">
            <label class="form-label text-xs font-bold uppercase tracking-wider text-muted mb-1.5">Academic Level *</label>
            <select name="level" class="form-control" required>
              <option value="">Select Level</option>
              <option value="Bachelor" {{ old('level', $program->level) == 'Bachelor' ? 'selected' : '' }}>Bachelor Degree</option>
              <option value="Diploma" {{ old('level', $program->level) == 'Diploma' ? 'selected' : '' }}>Diploma</option>
              <option value="Certificate" {{ old('level', $program->level) == 'Certificate' ? 'selected' : '' }}>Certificate</option>
              <option value="Postgraduate" {{ old('level', $program->level) == 'Postgraduate' ? 'selected' : '' }}>Postgraduate (Master/PhD)</option>
            </select>
            @error('level') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
          </div>
          <div class="form-group">
            <label class="form-label text-xs font-bold uppercase tracking-wider text-muted mb-1.5">Duration *</label>
            <input type="text" name="duration" class="form-control" value="{{ old('duration', $program->duration) }}" required>
            @error('duration') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="form-group">
            <label class="form-label text-xs font-bold uppercase tracking-wider text-muted mb-1.5">Delivery Mode *</label>
            <select name="delivery_mode" class="form-control" required>
              <option value="Full-time" {{ old('delivery_mode', $program->delivery_mode) == 'Full-time' ? 'selected' : '' }}>Full-time</option>
              <option value="Evening" {{ old('delivery_mode', $program->delivery_mode) == 'Evening' ? 'selected' : '' }}>Evening</option>
              <option value="Full-time and Evening" {{ old('delivery_mode', $program->delivery_mode) == 'Full-time and Evening' ? 'selected' : '' }}>Both Full-time & Evening</option>
              <option value="Online" {{ old('delivery_mode', $program->delivery_mode) == 'Online' ? 'selected' : '' }}>Online</option>
            </select>
            @error('delivery_mode') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
          </div>
          <div class="form-group">
            <label class="form-label text-xs font-bold uppercase tracking-wider text-muted mb-1.5">Intake Session *</label>
            <input type="text" name="session" class="form-control" value="{{ old('session', $program->session) }}" required>
            @error('session') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="form-group">
          <label class="form-label text-xs font-bold uppercase tracking-wider text-muted mb-1.5">Status *</label>
          <select name="is_active" class="form-control" required>
            <option value="1" {{ old('is_active', $program->is_active) == '1' ? 'selected' : '' }}>Active</option>
            <option value="0" {{ old('is_active', $program->is_active) == '0' ? 'selected' : '' }}>Inactive</option>
          </select>
          @error('is_active') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-muted/10">
          <a href="{{ route('programs.index') }}" class="btn btn-ghost">Cancel</a>
          <button type="submit" class="btn btn-primary px-8">Update Programme</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
