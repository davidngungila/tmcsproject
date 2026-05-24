@extends('layouts.app')

@section('title', 'Edit Resource - Administration')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.resources.index') }}" class="text-sm text-muted hover:text-green-600 flex items-center gap-1 mb-2">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        Back to Documents
    </a>
    <h1 class="text-2xl font-bold">Edit Resource</h1>
</div>

<div class="card max-w-3xl">
    <div class="card-body">
        <form action="{{ route('admin.resources.update', $resource->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-row mb-4">
                <div class="form-group">
                    <label class="form-label">Resource Title</label>
                    <input type="text" name="title" class="form-control @error('title') border-red-500 @enderror" placeholder="e.g. Morning Prayer" value="{{ old('title', $resource->title) }}" required>
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-control" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $resource->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group mb-2">
                <label class="form-label">File (Leave blank to keep current file)</label>
                <input type="file" name="file" class="form-control">
                @error('file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="mb-4">
                <p class="text-xs text-muted">Current file: <span class="font-mono">{{ basename($resource->file_path) }}</span> ({{ strtoupper($resource->file_type) }}, {{ round($resource->file_size / 1024 / 1024, 2) }} MB)</p>
            </div>

            <div class="form-group mb-4">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4" placeholder="Brief excerpt or description of the document...">{{ old('description', $resource->description) }}</textarea>
            </div>

            <div class="form-group mb-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $resource->is_featured) ? 'checked' : '' }} class="w-4 h-4 rounded text-green-600 focus:ring-green-500 border-gray-300">
                    <span class="text-sm font-semibold">Mark as Featured Resource</span>
                </label>
                <p class="text-xs text-muted mt-1 ml-6">Featured resources are highlighted at the top of the library.</p>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.resources.index') }}" class="px-6 py-2 border border-border rounded-lg hover:bg-bg-base transition font-semibold">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold">
                    Update Resource
                </button>
            </div>
        </form>
    </div>
</div>
@endsection