@extends('layouts.app')

@section('title', 'Add Category - TmcsSmart')
@section('page-title', 'Add Member Category')
@section('breadcrumb', 'TmcsSmart / Members / Categories / Add')

@section('content')
<div class="animate-in">
  <div class="max-w-2xl">
    <div class="card">
      <div class="card-header border-b">
        <h3 class="card-title">Category Details</h3>
      </div>
      <div class="card-body">
        <form action="{{ route('members.categories.store') }}" method="POST">
          @csrf
          <div class="grid grid-cols-1 gap-4">
            <div class="form-group">
              <label class="form-label">Category Name</label>
              <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="e.g. Undergraduate">
              @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
              <label class="form-label">Description</label>
              <textarea name="description" class="form-control" rows="3" placeholder="Enter category description...">{{ old('description') }}</textarea>
              @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div class="form-group">
                <label class="form-label">Color</label>
                <select name="color" class="form-control">
                  <option value="blue">Blue</option>
                  <option value="green">Green</option>
                  <option value="purple">Purple</option>
                  <option value="amber">Amber</option>
                  <option value="red">Red</option>
                  <option value="indigo">Indigo</option>
                  <option value="pink">Pink</option>
                </select>
              </div>

              <div class="form-group">
                <label class="form-label">Icon</label>
                <select name="icon" class="form-control">
                  <option value="tag">Default (Tag)</option>
                  <option value="academic-cap">Academic Cap</option>
                  <option value="user-group">User Group</option>
                  <option value="briefcase">Briefcase</option>
                  <option value="home">Home</option>
                  <option value="star">Star</option>
                </select>
              </div>
            </div>

            <div class="flex items-center gap-2 mt-2">
              <input type="checkbox" name="is_active" id="is_active" value="1" checked>
              <label for="is_active" class="text-sm">Active</label>
            </div>
          </div>

          <div class="flex gap-3 mt-8">
            <button type="submit" class="btn btn-primary">Create Category</button>
            <a href="{{ route('members.categories') }}" class="btn btn-secondary">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
