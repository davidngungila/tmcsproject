@extends('layouts.app')

@section('title', 'Make Payment - TmcsSmart')
@section('page-title', 'Online Contribution')
@section('breadcrumb', 'TmcsSmart / Member / Pay')

@section('content')
<div class="animate-in max-w-xl mx-auto">
  <div class="card">
    <div class="card-header border-b">
      <div class="card-title">Electronic Giving</div>
      <div class="card-subtitle">Securely pay your contributions via Mobile Money or Card.</div>
    </div>
    <div class="card-body">
      <form action="{{ route('member.profile.process-payment') }}" method="POST">
        @csrf

        <div class="space-y-6">
          <div class="form-group">
            <label class="form-label">Contribution Type *</label>
            <select name="contribution_type" class="form-control" required>
              <option value="">Select Type</option>
              <option value="Tithes">Tithes</option>
              <option value="Offerings">Offerings</option>
              <option value="Building Fund">Building Fund</option>
              <option value="Community Fund">Community Fund</option>
              <option value="Special Giving">Special Giving</option>
              <option value="Other">Other</option>
            </select>
            @error('contribution_type') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
          </div>

          <div class="form-group">
            <label class="form-label">Amount (TZS) *</label>
            <div class="relative">
              <input type="number" name="amount" class="form-control" value="{{ old('amount', 1000) }}" min="500" required>
              <div class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-muted">TZS</div>
            </div>
            <p class="text-[10px] text-muted mt-1">Minimum contribution: 500 TZS</p>
            @error('amount') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
          </div>

          <div class="form-group">
            <label class="form-label">Payment Method *</label>
            <div class="grid grid-cols-2 gap-4">
              <label class="cursor-pointer">
                <input type="radio" name="payment_method" value="mobile_money" class="peer hidden" checked>
                <div class="p-4 border-2 border-light rounded-xl peer-checked:border-green-500 peer-checked:bg-green-50 transition-all flex flex-col items-center gap-2">
                  <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                  <span class="text-xs font-bold">Mobile Money</span>
                </div>
              </label>
              <label class="cursor-pointer">
                <input type="radio" name="payment_method" value="card" class="peer hidden">
                <div class="p-4 border-2 border-light rounded-xl peer-checked:border-green-500 peer-checked:bg-green-50 transition-all flex flex-col items-center gap-2">
                  <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                  <span class="text-xs font-bold">Bank Card</span>
                </div>
              </label>
            </div>
          </div>

          <div class="p-4 bg-light rounded-xl border border-gray-100 flex items-start gap-3">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="text-blue-500 mt-0.5"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div class="text-[11px] text-muted leading-relaxed">
              For Mobile Money, you will receive a prompt on your phone <strong>({{ $member->phone }})</strong> to enter your PIN. Ensure your phone is nearby and unlocked.
            </div>
          </div>
        </div>

        <div class="flex gap-3 mt-8">
          <a href="{{ route('member.profile.index') }}" class="btn btn-secondary flex-1 text-center">Cancel</a>
          <button type="submit" class="btn btn-primary flex-1">Initiate Payment</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
