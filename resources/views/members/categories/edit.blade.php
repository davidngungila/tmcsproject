@extends('layouts.app')

@section('title', 'Edit Category - TmcsSmart')
@section('page-title', 'Edit Member Category')
@section('breadcrumb', 'TmcsSmart / Members / Categories / Edit')

@section('content')
<div class="animate-in">
  <div class="max-w-2xl">
    <div class="card">
      <div class="card-header border-b">
        <h3 class="card-title">Edit Details: {{ $memberCategory->name }}</h3>
      </div>
      <div class="card-body">
        <form action="{{ route('members.categories.update', $memberCategory->id) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="grid grid-cols-1 gap-4">
            <div class="form-group">
              <label class="form-label">Category Name</label>
              <input type="text" name="name" class="form-control" value="{{ old('name', $memberCategory->name) }}" required>
              @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
              <label class="form-label">Description</label>
              <textarea name="description" class="form-control" rows="3">{{ old('description', $memberCategory->description) }}</textarea>
              @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div class="form-group">
                <label class="form-label">Color</label>
                <select name="color" class="form-control">
                  @foreach(['blue', 'green', 'purple', 'amber', 'red', 'indigo', 'pink'] as $color)
                  <option value="{{ $color }}" {{ (old('color', $memberCategory->color) == $color) ? 'selected' : '' }}>
                    {{ ucfirst($color) }}
                  </option>
                  @endforeach
                </select>
              </div>

              <div class="form-group">
                <label class="form-label">Icon</label>
                <select name="icon" class="form-control">
                  @foreach(['tag' => 'Default (Tag)', 'academic-cap' => 'Academic Cap', 'user-group' => 'User Group', 'briefcase' => 'Briefcase', 'home' => 'Home', 'star' => 'Star'] as $val => $label)
                  <option value="{{ $val }}" {{ (old('icon', $memberCategory->icon) == $val) ? 'selected' : '' }}>
                    {{ $label }}
                  </option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="flex items-center gap-2 mt-2">
              <input type="hidden" name="is_active" value="0">
              <input type="checkbox" name="is_active" id="is_active" value="1" {{ $memberCategory->is_active ? 'checked' : '' }}>
              <label for="is_active" class="text-sm">Active</label>
            </div>
          </div>

          <div class="flex gap-3 mt-8">
            <button type="submit" class="btn btn-primary">Update Category</button>
            <a href="{{ route('members.categories') }}" class="btn btn-secondary">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
