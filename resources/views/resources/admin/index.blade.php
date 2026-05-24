@extends('layouts.app')

@section('title', 'Resources Management - Administration')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold">Documents Management</h1>
        <p class="text-muted text-sm">Manage library resources and uploads.</p>
    </div>
    <a href="{{ route('admin.resources.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold flex items-center gap-2">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Upload Resource
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="w-full text-left">
            <thead>
                <tr class="border-bottom border-light">
                    <th class="px-6 py-4 text-xs font-bold text-muted uppercase">Title</th>
                    <th class="px-6 py-4 text-xs font-bold text-muted uppercase">Category</th>
                    <th class="px-6 py-4 text-xs font-bold text-muted uppercase">Type</th>
                    <th class="px-6 py-4 text-xs font-bold text-muted uppercase">Stats</th>
                    <th class="px-6 py-4 text-xs font-bold text-muted uppercase">Featured</th>
                    <th class="px-6 py-4 text-xs font-bold text-muted uppercase text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($resources as $resource)
                <tr class="border-bottom border-light hover:bg-hover-row transition">
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="font-semibold">{{ $resource->title }}</span>
                            <span class="text-xs text-muted">{{ $resource->created_at->format('M d, Y') }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 bg-green-50 text-green-700 text-xs font-semibold rounded">
                            {{ $resource->category->name }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-xs font-bold uppercase">{{ $resource->file_type }}</span>
                        <span class="text-[10px] text-muted block">{{ round($resource->file_size / 1024 / 1024, 2) }} MB</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-3 text-xs text-muted">
                            <span title="Views">👁️ {{ $resource->view_count }}</span>
                            <span title="Downloads">📥 {{ $resource->download_count }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($resource->is_featured)
                        <span class="w-2 h-2 rounded-full bg-gold-400 inline-block"></span>
                        <span class="text-xs text-gold-500 font-semibold ml-1">Featured</span>
                        @else
                        <span class="text-xs text-muted">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('resources.show', $resource->slug) }}" target="_blank" class="p-2 text-muted hover:text-green-600 transition">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                            <a href="{{ route('admin.resources.edit', $resource->id) }}" class="p-2 text-muted hover:text-green-600 transition">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form action="{{ route('admin.resources.destroy', $resource->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
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
                    <td colspan="6" class="px-6 py-10 text-center text-muted">No resources found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $resources->links() }}
</div>
@endsection