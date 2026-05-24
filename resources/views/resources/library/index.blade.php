@extends('layouts.app')

@section('title', 'Resource Library - TmcsSmart')

@push('styles')
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        transition: all 0.3s ease;
    }
    .glass-card:hover {
        background: rgba(255, 255, 255, 0.08);
        transform: translateY(-5px);
        border-color: var(--green-400);
    }
    .category-chip {
        padding: 8px 18px;
        border-radius: 99px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        border: 1px solid var(--border);
        background: var(--bg-card);
        color: var(--text-secondary);
    }
    .category-chip.active {
        background: var(--green-600);
        color: white;
        border-color: var(--green-600);
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
    }
    .featured-banner {
        background: linear-gradient(135deg, var(--green-900), var(--green-700));
        border-radius: 24px;
        padding: 40px;
        position: relative;
        overflow: hidden;
    }
    .bg-orb {
        position: absolute;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: radial-gradient(circle, var(--green-400) 0%, transparent 70%);
        filter: blur(60px);
        opacity: 0.15;
        z-index: 0;
    }
    .resource-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 24px;
    }
    .badge-pdf { background: #fee2e2; color: #b91c1c; }
    .badge-docx { background: #dbeafe; color: #1e40af; }
</style>
@endpush

@section('content')
<div class="relative">
    <!-- Background Orbs -->
    <div class="bg-orb top-0 right-0"></div>
    <div class="bg-orb bottom-0 left-0"></div>

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold mb-2">Resource Library</h1>
            <p class="text-muted">Browse and search for prayer documents, novenas, and rosaries.</p>
        </div>
        <div class="flex gap-3">
            <div class="search-bar">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <form action="{{ route('resources.library') }}" method="GET">
                    <input type="text" name="search" placeholder="Search title or category..." value="{{ request('search') }}">
                </form>
            </div>
        </div>
    </div>

    @if($featuredResources->count() > 0)
    <!-- Featured Section -->
    <div class="featured-banner mb-12 text-white">
        <div class="relative z-10">
            <span class="inline-block px-3 py-1 bg-gold-400 text-black text-xs font-bold rounded-full mb-4">FEATURED</span>
            <div class="flex flex-col md:flex-row gap-8">
                @foreach($featuredResources as $featured)
                <div class="flex-1 glass-card p-6 border-none">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center">
                            @if($featured->file_type == 'pdf')
                                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            @else
                                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            @endif
                        </div>
                        <span class="text-xs opacity-60">{{ $featured->created_at->format('M d, Y') }}</span>
                    </div>
                    <h3 class="text-xl font-bold mb-2">{{ $featured->title }}</h3>
                    <p class="text-sm opacity-80 mb-6 line-clamp-2">{{ $featured->description }}</p>
                    <a href="{{ route('resources.show', $featured->slug) }}" class="inline-flex items-center gap-2 text-sm font-bold text-green-300 hover:text-white transition">
                        Read Now
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
                @if($loop->iteration == 2) @break @endif
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Category Filters -->
    <div class="flex gap-3 mb-8 overflow-x-auto pb-2">
        <a href="{{ route('resources.library', ['category' => 'all', 'search' => request('search')]) }}" 
           class="category-chip {{ !request('category') || request('category') == 'all' ? 'active' : '' }}">
            All Resources
        </a>
        @foreach($categories as $category)
        <a href="{{ route('resources.library', ['category' => $category->slug, 'search' => request('search')]) }}" 
           class="category-chip {{ request('category') == $category->slug ? 'active' : '' }}">
            {{ $category->name }} ({{ $category->resources_count }})
        </a>
        @endforeach
    </div>

    <!-- Resource Grid -->
    <div class="resource-grid mb-12">
        @forelse($resources as $resource)
        <div class="glass-card p-5 group">
            <div class="flex justify-between items-start mb-4">
                <div class="px-2 py-1 rounded text-[10px] font-bold uppercase {{ $resource->file_type == 'pdf' ? 'badge-pdf' : 'badge-docx' }}">
                    {{ $resource->file_type }}
                </div>
                <button onclick="toggleBookmark('{{ $resource->slug }}')" class="text-muted hover:text-red-500 transition">
                    <svg id="bookmark-{{ $resource->slug }}" width="18" height="18" fill="{{ optional($resource->interactions->firstWhere('user_id', auth()->id()))->is_bookmarked ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                </button>
            </div>
            
            <h4 class="font-bold text-lg mb-2 group-hover:text-green-600 transition">{{ $resource->title }}</h4>
            <p class="text-sm text-muted mb-4 line-clamp-2">{{ $resource->description }}</p>
            
            <div class="flex items-center gap-4 text-xs text-muted mb-6">
                <span class="flex items-center gap-1">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    {{ $resource->view_count }}
                </span>
                <span class="flex items-center gap-1">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    {{ $resource->download_count }}
                </span>
                <span class="flex items-center gap-1">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ round($resource->file_size / 1024 / 1024, 2) }} MB
                </span>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('resources.show', $resource->slug) }}" class="flex-1 py-2 rounded-lg bg-green-600 text-white text-center text-sm font-semibold hover:bg-green-700 transition">View</a>
                <a href="{{ route('resources.download', $resource->slug) }}" class="w-10 h-10 flex items-center justify-center rounded-lg border border-border hover:bg-green-50 hover:text-green-600 transition">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 text-center">
            <div class="w-20 h-20 bg-muted/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg width="32" height="32" class="text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-xl font-bold mb-1">No resources found</h3>
            <p class="text-muted">Try adjusting your search or filters.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $resources->links() }}
    </div>
</div>

@push('scripts')
<script>
    function toggleBookmark(slug) {
        fetch(`/resources/${slug}/bookmark`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const svg = document.getElementById(`bookmark-${slug}`);
            if (data.is_bookmarked) {
                svg.setAttribute('fill', 'currentColor');
                svg.classList.add('text-red-500');
            } else {
                svg.setAttribute('fill', 'none');
                svg.classList.remove('text-red-500');
            }
        });
    }
</script>
@endpush
@endsection