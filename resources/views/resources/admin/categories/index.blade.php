@extends('layouts.app')

@section('title', 'Resource Categories - Administration')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold">Resource Categories</h1>
        <p class="text-muted text-sm">Manage categories for the resource library.</p>
    </div>
    <a href="{{ route('admin.resource-categories.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold flex items-center gap-2">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        New Category
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="w-full text-left">
            <thead>
                <tr class="border-bottom border-light">
                    <th class="px-6 py-4 text-xs font-bold text-muted uppercase">Name</th>
                    <th class="px-6 py-4 text-xs font-bold text-muted uppercase">Slug</th>
                    <th class="px-6 py-4 text-xs font-bold text-muted uppercase">Resources</th>
                    <th class="px-6 py-4 text-xs font-bold text-muted uppercase text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr class="border-bottom border-light hover:bg-hover-row transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded bg-green-100 text-green-600 flex items-center justify-center font-bold">
                                {{ substr($category->name, 0, 1) }}
                            </div>
                            <span class="font-semibold">{{ $category->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-muted">{{ $category->slug }}</td>
                    <td class="px-6 py-4 text-sm font-semibold">{{ $category->resources_count }}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.resource-categories.edit', $category->id) }}" class="p-2 text-muted hover:text-green-600 transition">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form action="{{ route('admin.resource-categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-muted hover:text-red-500 transition">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-10 text-center text-muted">No categories found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $categories->links() }}
</div>
@endsection