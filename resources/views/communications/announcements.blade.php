@extends('layouts.app')

@section('title', 'Announcements - TmcsSmart')
@section('page-title', 'Announcements')
@section('breadcrumb', 'TmcsSmart / Communications / Announcements')

@section('content')
<div class="animate-in">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-bold">Announcements</h2>
            <p class="text-sm text-muted mt-1">View and manage church-wide announcements</p>
        </div>
        <a href="{{ route('communications.create') }}" class="btn btn-primary">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
            New Announcement
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($announcements as $announcement)
            <div class="card hover:shadow-lg transition-all border-l-4 {{ $announcement->status == 'Sent' ? 'border-green-500' : 'border-amber-500' }}">
                <div class="card-body">
                    <div class="flex justify-between items-start mb-4">
                        <span class="badge {{ $announcement->status == 'Sent' ? 'green' : 'gold' }}">{{ $announcement->status }}</span>
                        <span class="text-[10px] text-muted">{{ $announcement->created_at->diffForHumans() }}</span>
                    </div>
                    <h3 class="font-bold text-sm mb-2">{{ $announcement->subject }}</h3>
                    <p class="text-xs text-muted line-clamp-3 mb-4">{{ $announcement->message }}</p>
                    <div class="flex items-center justify-between pt-4 border-t border-light">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-light flex items-center justify-center text-[10px] font-bold">
                                {{ substr($announcement->sender->name ?? 'S', 0, 1) }}
                            </div>
                            <span class="text-[10px] text-muted">{{ $announcement->sender->name ?? 'System' }}</span>
                        </div>
                        <a href="{{ route('communications.show', $announcement->id) }}" class="text-green-600 text-xs font-bold hover:underline">Read More</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12 text-muted card">
                <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" class="mx-auto mb-4 opacity-20"><path d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                <p>No announcements found.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $announcements->links() }}
    </div>
</div>
@endsection
