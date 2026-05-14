@extends('layouts.app')

@section('title', 'Add API Config - TmcsSmart')
@section('page-title', 'Add API Config')
@section('breadcrumb', 'TmcsSmart / Settings / API Config / Create')

@section('content')
<div class="animate-in">
  <div class="card max-w-2xl mx-auto">
    <div class="card-header">
      <div class="card-title">New API Configuration</div>
      <div class="card-subtitle">Set up a new messaging or email gateway</div>
    </div>
    <div class="card-body">
      <form action="{{ route('api-configs.store') }}" method="POST" class="space-y-4">
        @csrf
        <div class="form-group">
          <label class="form-label">Provider Name *</label>
          <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="e.g. NextSMS, Twilio, Mailgun" required>
          @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
          <label class="form-label">Provider Type *</label>
          <select name="provider_type" class="form-control" required>
            <option value="SMS" {{ old('provider_type') == 'SMS' ? 'selected' : '' }}>SMS Gateway</option>
            <option value="Email" {{ old('provider_type') == 'Email' ? 'selected' : '' }}>Email Provider</option>
            <option value="WhatsApp" {{ old('provider_type') == 'WhatsApp' ? 'selected' : '' }}>WhatsApp API</option>
          </select>
          @error('provider_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
          <label class="form-label">API Endpoint</label>
          <input type="url" name="api_endpoint" class="form-control" value="{{ old('api_endpoint') }}" placeholder="https://api.example.com/v1">
          @error('api_endpoint') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">API Key / Username</label>
            <input type="text" name="api_key" class="form-control" value="{{ old('api_key') }}">
            @error('api_key') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
          </div>
          <div class="form-group">
            <label class="form-label">API Secret / Password</label>
            <input type="password" name="api_secret" class="form-control">
            @error('api_secret') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Sender ID / From Email</label>
          <input type="text" name="sender_id" class="form-control" value="{{ old('sender_id') }}" placeholder="e.g. TMCS_MOCU or church@example.com">
          @error('sender_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }} class="rounded">
            <span class="text-sm font-medium">Set as Active Gateway</span>
          </label>
          <p class="text-[10px] text-muted mt-1">Activating this will deactivate other gateways of the same type.</p>
        </div>

        <div class="flex gap-3 pt-4">
          <a href="{{ route('api-configs.index') }}" class="btn btn-secondary flex-1 text-center">Cancel</a>
          <button type="submit" class="btn btn-primary flex-1">Save Configuration</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
