@extends('layouts.app')

@section('title', 'Group Giving - ' . $group->name)
@section('page-title', 'Record Meeting Giving: ' . $group->name)
@section('breadcrumb', 'Home / Group / Operations / Giving')

@section('content')
<div class="animate-in">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- RECORD NEW -->
        <div class="lg:col-span-1">
            <div class="card shadow-lg border-green-100">
                <div class="card-header border-b bg-green-50/30 py-4">
                    <h3 class="card-title text-green-800 uppercase font-black text-xs tracking-widest">General Meeting Giving</h3>
                </div>
                <div class="card-body p-6">
                    <form action="{{ route('groups.operations.contributions.store', $group->id) }}" method="POST">
                        @csrf
                        <div class="form-group mb-6">
                            <label class="form-label text-[10px] font-black uppercase text-gray-400 tracking-widest mb-2 block">Meeting Date</label>
                            <input type="date" name="meeting_date" class="form-control rounded-xl border-gray-200 p-4 font-bold" value="{{ date('Y-m-d') }}" required>
                        </div>
                        
                        <div class="form-group mb-6">
                            <label class="form-label text-[10px] font-black uppercase text-gray-400 tracking-widest mb-2 block">Total Amount Collected (TZS)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-xs font-black text-gray-400">TZS</span>
                                </div>
                                <input type="number" step="0.01" name="total_amount" 
                                       class="form-control pl-12 py-4 rounded-xl border-gray-200 font-black text-lg focus:ring-green-500 focus:border-green-500" 
                                       placeholder="0.00" required>
                            </div>
                            <p class="text-[10px] text-muted mt-2 font-medium italic">Record the total sum collected from all members and guests during this meeting.</p>
                        </div>

                        <div class="form-group mb-6">
                            <label class="form-label text-[10px] font-black uppercase text-gray-400 tracking-widest mb-2 block">Notes (Optional)</label>
                            <textarea name="notes" class="form-control rounded-xl border-gray-200 p-4 text-sm" rows="3" placeholder="e.g. Weekly fellowship collection..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-full py-4 font-black uppercase tracking-[0.2em] text-xs shadow-green-100 shadow-lg">
                            Save Meeting Giving
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- HISTORY -->
        <div class="lg:col-span-2">
            <div class="card border-none shadow-sm">
                <div class="card-header border-b p-6 bg-gray-50/50">
                    <h3 class="card-title text-xs font-black uppercase tracking-widest text-gray-400">Giving History</h3>
                </div>
                <div class="table-wrap">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Total Collected</th>
                                <th>Notes</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($meetings as $meeting)
                            <tr>
                                <td class="font-bold text-sm">{{ $meeting->meeting_date->format('M d, Y') }}</td>
                                <td class="font-black text-green-600">TZS {{ number_format($meeting->total_collected, 0) }}</td>
                                <td class="text-xs text-muted font-medium">{{ Str::limit($meeting->notes, 40) ?: 'General giving' }}</td>
                                <td class="text-right">
                                    <a href="{{ route('groups.operations.meeting.show', [$group->id, $meeting->id]) }}" class="btn btn-ghost btn-sm text-green-600 font-bold uppercase text-[10px] tracking-widest">View Details</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-16 text-muted italic">No giving records found for this community.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer border-t bg-gray-50/30">
                    {{ $meetings->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
