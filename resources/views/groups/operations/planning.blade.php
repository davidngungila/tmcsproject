@extends('layouts.app')

@section('title', 'Group Planning - ' . $group->name)
@section('page-title', 'Group Planning: ' . $group->name)
@section('breadcrumb', 'Home / Group / Operations / Planning')

@section('content')
<div class="animate-in">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- ADD PLAN -->
        <div class="lg:col-span-1">
            <div class="card">
                <div class="card-header border-b bg-green-50/30">
                    <h3 class="card-title text-green-800 uppercase font-black text-xs">Create New Group Plan</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('groups.operations.planning.store', $group->id) }}" method="POST">
                        @csrf
                        <div class="form-group mb-4">
                            <label class="form-label">Plan Title</label>
                            <input type="text" name="title" class="form-control" placeholder="e.g. Annual Retreat 2026" required>
                        </div>
                        <div class="form-group mb-4">
                            <label class="form-label">Estimated Budget</label>
                            <div class="input-group">
                                <span class="input-group-text text-[10px] font-black">TZS</span>
                                <input type="number" step="0.01" name="budget_amount" class="form-control" value="0">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="form-group">
                                <label class="form-label">Start Date</label>
                                <input type="date" name="start_date" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">End Date</label>
                                <input type="date" name="end_date" class="form-control">
                            </div>
                        </div>
                        <div class="form-group mb-4">
                            <label class="form-label">Description/Goals</label>
                            <textarea name="description" class="form-control" rows="4" placeholder="What do we want to achieve?"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-full py-3 font-black uppercase tracking-widest text-xs">
                            Save Group Plan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- PLANS LIST -->
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header border-b">
                    <h3 class="card-title text-sm font-bold uppercase tracking-wider text-muted">Group Roadmap</h3>
                </div>
                <div class="card-body p-0">
                    <div class="divide-y divide-gray-100">
                        @forelse($plans as $plan)
                        <div class="p-6 hover:bg-light/30 transition-all">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <h4 class="font-black text-gray-800">{{ $plan->title }}</h4>
                                        <span class="badge {{ $plan->status == 'active' ? 'green' : ($plan->status == 'draft' ? 'blue' : 'gray') }} text-[9px] uppercase font-bold">{{ $plan->status }}</span>
                                    </div>
                                    <p class="text-xs text-muted">{{ $plan->description }}</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-[9px] font-bold text-muted uppercase">Budget</div>
                                    <div class="text-sm font-black text-green-600">TZS {{ number_format($plan->budget_amount, 0) }}</div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between mt-4">
                                <div class="flex items-center gap-4">
                                    <div class="text-[10px] text-muted font-bold uppercase">
                                        <svg width="12" height="12" class="inline mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        {{ $plan->start_date->format('M d, Y') }} - {{ $plan->end_date ? $plan->end_date->format('M d, Y') : 'Ongoing' }}
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <button class="btn btn-ghost btn-sm text-xs">Edit</button>
                                    <button class="btn btn-ghost btn-sm text-xs text-red">Cancel</button>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="p-12 text-center">
                            <div class="w-16 h-16 rounded-full bg-gray-50 text-gray-300 flex-center mx-auto mb-4">
                                <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </div>
                            <p class="text-muted text-sm">No plans recorded yet. Start planning for your community!</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
