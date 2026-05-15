@extends('layouts.app')

@section('title', 'Meeting Details - ' . $group->name)
@section('page-title', 'Meeting Audit: ' . $meeting->meeting_date->format('M d, Y'))
@section('breadcrumb', 'Home / Group / Operations / Meeting Details')

@section('content')
<div class="animate-in max-w-4xl mx-auto">
    <div class="card shadow-xl border-none overflow-hidden">
        <div class="card-header bg-green-900 p-8 text-white flex items-center justify-between">
            <div>
                <h3 class="text-2xl font-black mb-1">{{ $group->name }}</h3>
                <p class="text-xs font-bold uppercase tracking-widest opacity-60">Meeting Audit Report • {{ $meeting->meeting_date->format('l, F d, Y') }}</p>
            </div>
            <div class="text-right">
                <div class="text-[10px] font-black uppercase opacity-60 tracking-widest">Total Collected</div>
                <div class="text-3xl font-black">TZS {{ number_format($meeting->total_collected, 0) }}</div>
            </div>
        </div>
        
        <div class="card-body p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <!-- ATTENDANCE STATS -->
                <div>
                    <h4 class="text-xs font-black uppercase text-gray-400 tracking-widest mb-6 flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-green-500"></div>
                        Attendance Breakdown
                    </h4>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 rounded-2xl bg-gray-50 border border-gray-100">
                            <span class="text-sm font-bold text-gray-600">Present Members</span>
                            <span class="badge green font-black text-xs">{{ $meeting->present_count }}</span>
                        </div>
                        <div class="flex items-center justify-between p-4 rounded-2xl bg-gray-50 border border-gray-100">
                            <span class="text-sm font-bold text-gray-600">Guest Attendees</span>
                            <span class="badge amber font-black text-xs">{{ $meeting->guest_count }}</span>
                        </div>
                        <div class="flex items-center justify-between p-4 rounded-2xl bg-gray-50 border border-gray-100">
                            <span class="text-sm font-bold text-gray-600">Absentees</span>
                            <span class="badge red font-black text-xs">{{ $meeting->absent_count }}</span>
                        </div>
                        <div class="flex items-center justify-between p-4 rounded-2xl bg-gray-50 border border-gray-100">
                            <span class="text-sm font-bold text-gray-600">Apologies</span>
                            <span class="badge blue font-black text-xs">{{ $meeting->apology_count }}</span>
                        </div>
                    </div>
                </div>

                <!-- FINANCIALS & NOTES -->
                <div>
                    <h4 class="text-xs font-black uppercase text-gray-400 tracking-widest mb-6 flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-green-500"></div>
                        Meeting Notes & Financials
                    </h4>
                    <div class="p-6 rounded-3xl bg-green-50/50 border border-green-100 mb-6">
                        <div class="text-[10px] font-black uppercase text-green-600 tracking-widest mb-2">Giving Summary</div>
                        <p class="text-sm text-green-900 font-medium leading-relaxed">
                            A total of <span class="font-black">TZS {{ number_format($meeting->total_collected, 0) }}</span> was collected during this meeting. 
                            This has been recorded as general group offering.
                        </p>
                    </div>

                    <div class="p-6 rounded-3xl bg-gray-50 border border-gray-100">
                        <div class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-2">Auditor's Notes</div>
                        <p class="text-sm text-gray-700 leading-relaxed italic">
                            {{ $meeting->notes ?: 'No specific notes were recorded for this meeting session.' }}
                        </p>
                    </div>
                </div>
            </div>

            @if($meeting->attendances->count() > 0)
            <!-- INDIVIDUAL ATTENDEES LIST -->
            <div class="mt-12">
                <h4 class="text-xs font-black uppercase text-gray-400 tracking-widest mb-6 flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-green-500"></div>
                    Individual Member Records
                </h4>
                <div class="table-wrap border border-gray-100 rounded-3xl overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="text-[10px] font-black uppercase tracking-widest p-4">Member</th>
                                <th class="text-[10px] font-black uppercase tracking-widest p-4">Status</th>
                                <th class="text-[10px] font-black uppercase tracking-widest p-4 text-right">Contribution</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($meeting->attendances as $att)
                            <tr>
                                <td class="p-4">
                                    <div class="font-bold text-sm">{{ $att->member->full_name }}</div>
                                    <div class="text-[10px] text-muted">{{ $att->member->registration_number }}</div>
                                </td>
                                <td class="p-4">
                                    <span class="badge {{ $att->status == 'present' ? 'green' : ($att->status == 'absent' ? 'red' : 'blue') }} scale-90">
                                        {{ ucfirst($att->status) }}
                                    </span>
                                </td>
                                <td class="p-4 text-right font-black text-sm text-gray-700">
                                    {{ $att->contribution_amount > 0 ? 'TZS ' . number_format($att->contribution_amount, 0) : '-' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <div class="mt-12 pt-8 border-t border-gray-100 flex items-center justify-between">
                <div class="flex gap-4">
                    <button class="btn btn-secondary px-6 font-bold text-xs" onclick="window.print()">Print Audit</button>
                    <a href="{{ route('groups.operations.contributions', $group->id) }}" class="btn btn-ghost px-6 font-bold text-xs text-green-600">Back to History</a>
                </div>
                <div class="text-[10px] font-black uppercase text-gray-300 tracking-widest">
                    Recorded By: {{ auth()->user()->name }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
