@extends('layouts.app')

@section('title', 'Make Payment - TMCS Smart')
@section('page-title', 'Make Payment')
@section('breadcrumb', 'Home / Member / Pay')

@section('content')
<div class="animate-in">
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- PAYMENT FORM -->
    <div class="lg:col-span-2">
      <div class="card">
        <div class="card-header border-b">
          <div class="card-title text-sm font-bold uppercase tracking-wider text-muted">Electronic Giving Portal</div>
          <div class="card-subtitle">Securely contribute to church funds using mobile money or card.</div>
        </div>
        <div class="card-body">
          <form action="{{ route('member.profile.process-payment') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
              <div class="form-group">
                <label class="form-label text-xs font-bold">Contribution Type *</label>
                <select name="contribution_type" class="form-control" required>
                  <option value="">Select Purpose</option>
                  <option value="Tithes">Tithes (1/10)</option>
                  <option value="Offerings">General Offering</option>
                  <option value="Building Fund">Building & Construction</option>
                  <option value="Community Fund">Small Christian Community</option>
                  <option value="Special Giving">Special Projects</option>
                  <option value="Almsgiving">Almsgiving (Charity)</option>
                </select>
                @error('contribution_type') <div class="text-red text-[10px] mt-1">{{ $message }}</div> @enderror
              </div>

              <div class="form-group">
                <label class="form-label text-xs font-bold">Amount (TZS) *</label>
                <div class="relative">
                  <input type="number" name="amount" class="form-control pl-12" value="{{ old('amount', 1000) }}" min="500" required>
                  <div class="absolute left-4 top-1/2 -translate-y-1/2 text-xs font-bold text-muted border-r pr-2">TZS</div>
                </div>
                @error('amount') <div class="text-red text-[10px] mt-1">{{ $message }}</div> @enderror
              </div>
            </div>

            <div class="form-group mb-8">
              <label class="form-label text-xs font-bold mb-4 block">Select Payment Channel *</label>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <label class="relative cursor-pointer group">
                  <input type="radio" name="payment_method" value="mobile_money" class="peer hidden" checked>
                  <div class="p-4 border-2 border-light rounded-2xl peer-checked:border-green-500 peer-checked:bg-green-50 transition-all flex items-center gap-4">
                    <div class="w-10 h-10 rounded-lg bg-white flex-center text-green-600 shadow-sm">
                      <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                      <div class="text-xs font-bold">Mobile Money</div>
                      <div class="text-[9px] text-muted">M-Pesa, TigoPesa, AirtelMoney</div>
                    </div>
                    <div class="ml-auto opacity-0 peer-checked:opacity-100 text-green-500">
                      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                    </div>
                  </div>
                </label>

                <label class="relative cursor-pointer group">
                  <input type="radio" name="payment_method" value="card" class="peer hidden">
                  <div class="p-4 border-2 border-light rounded-2xl peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all flex items-center gap-4">
                    <div class="w-10 h-10 rounded-lg bg-white flex-center text-blue-600 shadow-sm">
                      <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    </div>
                    <div>
                      <div class="text-xs font-bold">Bank Card</div>
                      <div class="text-[9px] text-muted">Visa, Mastercard</div>
                    </div>
                    <div class="ml-auto opacity-0 peer-checked:opacity-100 text-blue-500">
                      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                    </div>
                  </div>
                </label>
              </div>
            </div>

            <div class="flex items-center gap-3 p-4 bg-blue-50/50 rounded-2xl border border-blue-100 mb-8">
              <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex-center flex-shrink-0">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              </div>
              <p class="text-[10px] text-blue-800 leading-relaxed">
                You will be redirected to the secure Snipe payment gateway. For mobile money, a USSD prompt will be sent to <strong>{{ $member->phone }}</strong>.
              </p>
            </div>

            <div class="flex gap-3">
              <a href="{{ route('member.profile.index') }}" class="btn btn-secondary flex-1 py-3 text-sm font-bold">Cancel</a>
              <button type="submit" class="btn btn-primary flex-1 py-3 text-sm font-bold shadow-lg shadow-green-200">
                Confirm & Pay Now
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- RECENT HISTORY SIDEBAR -->
    <div class="lg:col-span-1 space-y-6">
      <div class="card">
        <div class="card-header border-b">
          <div class="card-title text-sm">Recent Payments</div>
        </div>
        <div class="card-body p-0">
          <div class="divide-y divide-gray-50">
            @forelse($member->contributions()->latest()->limit(5)->get() as $c)
              <div class="p-4 flex items-center justify-between">
                <div>
                  <div class="text-xs font-bold">{{ $c->contribution_type }}</div>
                  <div class="text-[10px] text-muted">{{ $c->contribution_date->format('d M, Y') }}</div>
                </div>
                <div class="text-xs font-bold text-right">
                  <div>{{ number_format($c->amount) }}</div>
                  <div class="text-[9px] {{ $c->is_verified ? 'text-green-500' : 'text-amber-500' }}">
                    {{ $c->is_verified ? 'Verified' : 'Pending' }}
                  </div>
                </div>
              </div>
            @empty
              <div class="p-8 text-center text-xs text-muted">No recent payments.</div>
            @endforelse
          </div>
        </div>
        <div class="card-footer p-3 bg-light/30 border-t text-center">
          <a href="{{ route('member.contributions.index') }}" class="text-[10px] font-bold text-blue-500 hover:underline">View Full History</a>
        </div>
      </div>

      <!-- SAVED METHODS (PLACEHOLDER) -->
      <div class="card opacity-60 grayscale cursor-not-allowed">
        <div class="card-header border-b">
          <div class="card-title text-sm">Saved Methods</div>
        </div>
        <div class="card-body p-8 text-center">
          <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" class="mx-auto mb-2 opacity-30"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
          <p class="text-[10px]">Saved payment methods coming soon.</p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
