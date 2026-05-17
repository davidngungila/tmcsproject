@extends('layouts.app')

@section('title', 'My Contributions - TMCS Smart')
@section('page-title', 'Contributions')
@section('breadcrumb', 'Home / Member / Contributions')

@section('content')
<div class="animate-in space-y-6">
  <!-- STATS OVERVIEW -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="card p-6 bg-green-50 border-green-100 flex items-center gap-4">
      <div class="w-12 h-12 rounded-2xl bg-green-100 text-green-600 flex items-center justify-center">
        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
      <div>
        <div class="text-[10px] text-green-600 uppercase font-black tracking-widest">Total Verified</div>
        <div class="text-xl font-black text-green-700 tracking-tighter">{{ number_format($member->contributions()->where('is_verified', true)->sum('amount')) }} TZS</div>
      </div>
    </div>
    <div class="card p-6 bg-amber-50 border-amber-100 flex items-center gap-4">
      <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center">
        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
      <div>
        <div class="text-[10px] text-amber-600 uppercase font-black tracking-widest">Awaiting Verification</div>
        <div class="text-xl font-black text-amber-700 tracking-tighter">{{ number_format($member->contributions()->where('is_verified', false)->sum('amount')) }} TZS</div>
      </div>
    </div>
    <div class="card p-6 bg-blue-50 border-blue-100 flex items-center gap-4">
      <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center">
        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
      </div>
      <div>
        <div class="text-[10px] text-blue-600 uppercase font-black tracking-widest">Total Given</div>
        <div class="text-xl font-black text-blue-700 tracking-tighter">{{ number_format($member->contributions()->sum('amount')) }} TZS</div>
      </div>
    </div>
  </div>

  <div class="card overflow-hidden">
    <!-- Tab Headers -->
    <div class="card-header border-b p-0 bg-gray-50/30">
      <div class="flex">
        <button onclick="switchTab('verified')" id="verifiedTabBtn" class="flex-1 px-6 py-4 text-xs font-black uppercase tracking-widest border-b-2 border-green-600 text-green-600 bg-white transition-all">
          Verified Records ({{ $member->contributions()->where('is_verified', true)->count() }})
        </button>
        <button onclick="switchTab('pending')" id="pendingTabBtn" class="flex-1 px-6 py-4 text-xs font-black uppercase tracking-widest border-b-2 border-transparent text-muted hover:bg-gray-100 transition-all">
          Awaiting Verification ({{ $member->contributions()->where('is_verified', false)->count() }})
        </button>
      </div>
    </div>

    <!-- Verified Tab Content -->
    <div id="verifiedTabContent" class="tab-content">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead class="bg-light/30 border-b">
            <tr>
              <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-muted">Receipt #</th>
              <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-muted">Date</th>
              <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-muted">Purpose</th>
              <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-muted text-right">Amount</th>
              <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-muted text-center">Receipt</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @forelse($member->contributions()->where('is_verified', true)->latest()->get() as $contribution)
              <tr class="hover:bg-light/30 transition-colors">
                <td class="px-6 py-4 font-mono text-xs font-bold text-blue-600">#{{ $contribution->receipt_number }}</td>
                <td class="px-6 py-4 text-xs font-bold">{{ $contribution->contribution_date->format('d M, Y') }}</td>
                <td class="px-6 py-4 text-xs font-bold">{{ strtoupper(str_replace('_', ' ', $contribution->contribution_type)) }}</td>
                <td class="px-6 py-4 text-sm font-black text-right text-green-600">{{ number_format($contribution->amount) }}</td>
                <td class="px-6 py-4 text-center">
                  <div class="flex items-center justify-center gap-2">
                    <a href="{{ route('finance.receipt', $contribution->id) }}" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors" title="View">
                      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </a>
                    <a href="{{ route('finance.receipt', $contribution->id) }}?download=1" target="_blank" class="p-2 bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition-colors" title="Download">
                      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    </a>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="px-6 py-12 text-center">
                  <div class="text-muted text-xs font-bold">No verified contributions found.</div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- Pending Tab Content -->
    <div id="pendingTabContent" class="tab-content hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead class="bg-light/30 border-b">
            <tr>
              <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-muted">Reference</th>
              <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-muted">Initiated</th>
              <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-muted">Purpose</th>
              <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-muted text-right">Amount</th>
              <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-muted text-center">Status</th>
              <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-muted text-center">Action</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @forelse($member->contributions()->where('is_verified', false)->latest()->get() as $contribution)
              <tr class="hover:bg-light/30 transition-colors">
                <td class="px-6 py-4 font-mono text-xs font-bold text-gray-500">#{{ $contribution->receipt_number }}</td>
                <td class="px-6 py-4 text-xs font-bold">{{ $contribution->created_at->format('d M, h:i A') }}</td>
                <td class="px-6 py-4 text-xs font-bold text-muted">{{ strtoupper(str_replace('_', ' ', $contribution->contribution_type)) }}</td>
                <td class="px-6 py-4 text-sm font-black text-right text-amber-600">{{ number_format($contribution->amount) }}</td>
                <td class="px-6 py-4 text-center">
                  <span class="badge amber px-2 py-0.5 text-[9px] font-black uppercase">Pending</span>
                </td>
                <td class="px-6 py-4 text-center">
                  <div class="flex items-center justify-center gap-2">
                    <a href="{{ route('finance.receipt', $contribution->id) }}" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors" title="View Details">
                      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </a>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="px-6 py-12 text-center">
                  <div class="text-muted text-xs font-bold">No pending contributions found.</div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
function switchTab(tab) {
    const verifiedBtn = document.getElementById('verifiedTabBtn');
    const pendingBtn = document.getElementById('pendingTabBtn');
    const verifiedContent = document.getElementById('verifiedTabContent');
    const pendingContent = document.getElementById('pendingTabContent');

    if (tab === 'verified') {
        verifiedBtn.classList.add('border-green-600', 'text-green-600', 'bg-white');
        verifiedBtn.classList.remove('border-transparent', 'text-muted');
        pendingBtn.classList.add('border-transparent', 'text-muted');
        pendingBtn.classList.remove('border-green-600', 'text-green-600', 'bg-white');
        
        verifiedContent.classList.remove('hidden');
        pendingContent.classList.add('hidden');
    } else {
        pendingBtn.classList.add('border-green-600', 'text-green-600', 'bg-white');
        pendingBtn.classList.remove('border-transparent', 'text-muted');
        verifiedBtn.classList.add('border-transparent', 'text-muted');
        verifiedBtn.classList.remove('border-green-600', 'text-green-600', 'bg-white');
        
        pendingContent.classList.remove('hidden');
        verifiedContent.classList.add('hidden');
    }
}
</script>
@endpush
