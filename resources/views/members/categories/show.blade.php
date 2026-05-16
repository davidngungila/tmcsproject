@extends('layouts.app')

@section('title', 'Category Details - TmcsSmart')
@section('page-title', 'Category: ' . $memberCategory->name)
@section('breadcrumb', 'TmcsSmart / Members / Categories / ' . $memberCategory->name)

@section('content')
<div class="animate-in">
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="md:col-span-1">
      <div class="card">
        <div class="card-header border-b">
          <h3 class="card-title">Category Information</h3>
        </div>
        <div class="card-body">
          <div class="flex flex-col items-center py-6">
            <div class="w-20 h-20 rounded-full bg-{{ $memberCategory->color }}-100 text-{{ $memberCategory->color }}-600 flex items-center justify-center mb-4">
              @if($memberCategory->icon == 'academic-cap')
              <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
              @elseif($memberCategory->icon == 'user-group')
              <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              @elseif($memberCategory->icon == 'briefcase')
              <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
              @elseif($memberCategory->icon == 'home')
              <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
              @elseif($memberCategory->icon == 'star')
              <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-1.236 2.035-2.046 1.453l-3.976-2.888a1 1 0 00-1.175 0l-3.976 2.888c-.81.582-2.347-.531-2.046-1.453l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.381-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
              @else
              <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
              @endif
            </div>
            <h2 class="text-xl font-bold">{{ $memberCategory->name }}</h2>
            <span class="badge {{ $memberCategory->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} mt-2">
              {{ $memberCategory->is_active ? 'Active' : 'Inactive' }}
            </span>
          </div>
          
          <div class="space-y-4 mt-6">
            <div>
              <label class="text-xs font-bold text-muted uppercase">Description</label>
              <p class="text-sm mt-1">{{ $memberCategory->description ?: 'No description provided.' }}</p>
            </div>
            <div class="flex justify-between border-t pt-4">
              <span class="text-sm text-muted">Created</span>
              <span class="text-sm font-medium">{{ $memberCategory->created_at ? $memberCategory->created_at->format('M d, Y') : 'N/A' }}</span>
            </div>
          </div>

          <div class="flex gap-2 mt-8">
            <a href="{{ route('members.categories.edit', $memberCategory->id) }}" class="btn btn-primary flex-1">Edit</a>
            <a href="{{ route('members.categories') }}" class="btn btn-secondary">Back</a>
          </div>
        </div>
      </div>
    </div>

    <div class="md:col-span-2">
      <div class="card h-full">
        <div class="card-header border-b">
          <h3 class="card-title">Category Statistics</h3>
        </div>
        <div class="card-body">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="stat-card blue">
              <div class="stat-value">{{ $memberCategory->members->count() }}</div>
              <div class="stat-label">Total Members</div>
            </div>
            <div class="stat-card green">
              @php
                $thisMonth = $memberCategory->members->where('registration_date', '>=', now()->startOfMonth())->count();
                $total = $memberCategory->members->count();
                $growth = $total > 0 ? ($thisMonth / $total) * 100 : 0;
              @endphp
              <div class="stat-value">{{ round($growth, 1) }}%</div>
              <div class="stat-label">Growth (This Month)</div>
            </div>
          </div>

          <div class="mt-8">
            <h4 class="font-bold mb-4">Members in this Category</h4>
            @if($memberCategory->members->count() > 0)
            <div class="table-wrap">
              <table class="w-full">
                <thead>
                  <tr class="bg-muted/5 text-[10px] font-black uppercase tracking-widest text-muted">
                    <th class="px-4 py-3 text-left">Registration</th>
                    <th class="px-4 py-3 text-left">Name</th>
                    <th class="px-4 py-3 text-left">Contact</th>
                    <th class="px-4 py-3 text-right">Action</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-muted/10">
                  @foreach($memberCategory->members as $member)
                  <tr class="hover:bg-primary/5 transition-colors text-xs">
                    <td class="px-4 py-3 mono font-bold text-primary">{{ $member->registration_number }}</td>
                    <td class="px-4 py-3 font-bold">{{ $member->full_name }}</td>
                    <td class="px-4 py-3 text-muted">{{ $member->phone }}</td>
                    <td class="px-4 py-3 text-right">
                      <a href="{{ route('members.show', $member->id) }}" class="text-primary hover:text-primary-dark">View</a>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            @else
            <div class="text-center py-12 bg-gray-50 rounded border border-dashed">
              <p class="text-muted text-sm">No members found in this category yet.</p>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
