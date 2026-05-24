@extends('layouts.app')

@section('title', 'Edit Category - Administration')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.resource-categories.index') }}" class="text-sm text-muted hover:text-green-600 flex items-center gap-1 mb-2">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        Back to Categories
    </a>
    <h1 class="text-2xl font-bold">Edit Resource Category</h1>
</div>

<div class="card max-w-2xl">
    <div class="card-body">
        <form action="{{ route('admin.resource-categories.update', $resourceCategory->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group mb-4">
                <label class="form-label">Category Name</label>
                <input type="text" name="name" class="form-control @error('name') border-red-500 @enderror" placeholder="e.g. Prayers" value="{{ old('name', $resourceCategory->name) }}" required>
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="form-group mb-4">
                <label class="form-label">Icon (Lucide/SVG name)</label>
                <input type="text" name="icon" class="form-control" placeholder="e.g. book-open" value="{{ old('icon', $resourceCategory->icon) }}">
            </div>

            <div class="form-group mb-6">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4" placeholder="Brief description of this category...">{{ old('description', $resourceCategory->description) }}</textarea>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.resource-categories.index') }}" class="px-6 py-2 border border-border rounded-lg hover:bg-bg-base transition font-semibold">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold">
                    Update Category
                </button>
            </div>
        </form>
    </div>
</div>
@endsection