@extends('layouts.app')

@section('title', 'Finance Settings - TmcsSmart')
@section('page-title', 'Finance Settings')
@section('breadcrumb', 'TmcsSmart / Finance / Settings')

@section('content')
<div class="animate-in">
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- PAYMENT GATEWAYS -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Payment Gateways</h3>
        <p class="card-subtitle">Configure digital payment integrations</p>
      </div>
      <div class="card-body">
        <div class="space-y-6">
          <!-- SNIPE -->
          <div class="flex items-center justify-between p-4 bg-light rounded-lg">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 bg-white rounded-full flex-center shadow-sm">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
              </div>
              <div>
                <h4 class="font-bold text-sm">Snipe Payment API</h4>
                <p class="text-xs text-muted">Mobile Money, Card, QR</p>
              </div>
            </div>
            <span class="badge green">Connected</span>
          </div>

          <!-- CLICK PESA -->
          <div class="flex items-center justify-between p-4 bg-light rounded-lg opacity-60">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 bg-white rounded-full flex-center shadow-sm">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
              </div>
              <div>
                <h4 class="font-bold text-sm">Click Pesa</h4>
                <p class="text-xs text-muted">Bank & Mobile Wallets</p>
              </div>
            </div>
            <button class="btn btn-ghost btn-sm">Configure</button>
          </div>
        </div>
      </div>
    </div>

    <!-- CONTRIBUTION CATEGORIES -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Contribution Types</h3>
        <p class="card-subtitle">Manage available giving categories</p>
      </div>
      <div class="card-body">
        <div class="space-y-2">
          @foreach(['Almsgiving/Zaka', 'Tithe', 'Offering', 'Building Fund', 'Special Donation'] as $type)
          <div class="flex items-center justify-between p-3 border-b border-light last:border-0">
            <span class="text-sm font-medium">{{ $type }}</span>
            <div class="flex gap-2">
              <button class="text-blue hover:underline text-xs">Edit</button>
              <button class="text-red hover:underline text-xs">Disable</button>
            </div>
          </div>
          @endforeach
          <button class="btn btn-ghost btn-sm w-full mt-4">+ Add New Type</button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
