@extends('layouts.app')

@section('title', 'Group Reports - ' . $group->name)
@section('page-title', 'Group Reports Center')
@section('breadcrumb', 'Home / Groups / ' . $group->name . ' / Reports')

@section('content')
<div class="animate-in space-y-6">
    <!-- HEADER -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-black text-gray-800">{{ $group->name }} Reports</h2>
            <p class="text-sm text-muted font-medium mt-1">Select a report category to view detailed analytics and performance metrics.</p>
        </div>
        <a href="{{ route('groups.show', $group->id) }}" class="btn btn-secondary">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="mr-2"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Profile
        </a>
    </div>

    <!-- KPI SUMMARY -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card p-6 border-none shadow-sm bg-gradient-to-br from-green-600 to-green-700 text-white">
            <div class="text-[10px] font-black uppercase tracking-widest opacity-80">Total Membership</div>
            <div class="text-3xl font-black mt-2">{{ $group->members_count }}</div>
            <div class="text-[10px] font-bold mt-4 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                Active and Engaged
            </div>
        </div>
        <div class="card p-6 border-none shadow-sm bg-gradient-to-br from-blue-600 to-blue-700 text-white">
            <div class="text-[10px] font-black uppercase tracking-widest opacity-80">Total Giving</div>
            <div class="text-3xl font-black mt-2">TZS {{ number_format($totalCollected, 0) }}</div>
            <div class="text-[10px] font-bold mt-4 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-white opacity-50"></span>
                Since Inception
            </div>
        </div>
        <div class="card p-6 border-none shadow-sm bg-gradient-to-br from-amber-500 to-amber-600 text-white">
            <div class="text-[10px] font-black uppercase tracking-widest opacity-80">Active Projects</div>
            <div class="text-3xl font-black mt-2">{{ $activePlans }}</div>
            <div class="text-[10px] font-bold mt-4 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-white opacity-50"></span>
                Currently Running
            </div>
        </div>
    </div>

    <!-- REPORT CATEGORIES -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- FINANCIAL -->
        <a href="{{ route('groups.reports.view', [$group->id, 'financial']) }}" class="card p-8 border-none shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all group">
            <div class="flex items-start gap-6">
                <div class="w-14 h-14 rounded-2xl bg-green-50 text-green-600 flex-center group-hover:bg-green-600 group-hover:text-white transition-all">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-black text-gray-800">Financial Reports</h3>
                    <p class="text-sm text-muted mt-2 leading-relaxed">View giving history, meeting collections, and financial growth trends over time.</p>
                    <div class="mt-6 flex items-center text-[10px] font-black uppercase tracking-widest text-green-600">
                        Explore Analytics
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" class="ml-2 group-hover:translate-x-1 transition-transform"><path d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </div>
                </div>
            </div>
        </a>

        <!-- ADMINISTRATIVE -->
        <a href="{{ route('groups.reports.view', [$group->id, 'administrative']) }}" class="card p-8 border-none shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all group">
            <div class="flex items-start gap-6">
                <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex-center group-hover:bg-blue-600 group-hover:text-white transition-all">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-black text-gray-800">Administrative Reports</h3>
                    <p class="text-sm text-muted mt-2 leading-relaxed">Member demographics, growth statistics, and leadership structure analysis.</p>
                    <div class="mt-6 flex items-center text-[10px] font-black uppercase tracking-widest text-blue-600">
                        View Demographics
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" class="ml-2 group-hover:translate-x-1 transition-transform"><path d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </div>
                </div>
            </div>
        </a>

        <!-- MEETINGS -->
        <a href="{{ route('groups.reports.view', [$group->id, 'meetings']) }}" class="card p-8 border-none shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all group">
            <div class="flex items-start gap-6">
                <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex-center group-hover:bg-amber-600 group-hover:text-white transition-all">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-black text-gray-800">Meetings & Attendance</h3>
                    <p class="text-sm text-muted mt-2 leading-relaxed">Track attendance trends, meeting frequency, and member engagement levels.</p>
                    <div class="mt-6 flex items-center text-[10px] font-black uppercase tracking-widest text-amber-600">
                        Attendance Logs
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" class="ml-2 group-hover:translate-x-1 transition-transform"><path d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </div>
                </div>
            </div>
        </a>

        <!-- COMMUNICATION -->
        <a href="{{ route('groups.reports.view', [$group->id, 'communication']) }}" class="card p-8 border-none shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all group">
            <div class="flex items-start gap-6">
                <div class="w-14 h-14 rounded-2xl bg-purple-50 text-purple-600 flex-center group-hover:bg-purple-600 group-hover:text-white transition-all">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-black text-gray-800">Communication Reports</h3>
                    <p class="text-sm text-muted mt-2 leading-relaxed">Broadcast history, message delivery rates, and template usage statistics.</p>
                    <div class="mt-6 flex items-center text-[10px] font-black uppercase tracking-widest text-purple-600">
                        Message History
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" class="ml-2 group-hover:translate-x-1 transition-transform"><path d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection
