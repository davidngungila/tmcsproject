@extends('layouts.app')

@section('title', 'Edit API Config - TmcsSmart')
@section('page-title', 'Edit API Config')
@section('breadcrumb', 'TmcsSmart / Settings / API Config / Edit')

@section('content')
<div class="animate-in">
  <div class="card max-w-2xl mx-auto">
    <div class="card-header">
      <div class="card-title">Edit: {{ $apiConfig->name }}</div>
      <div class="card-subtitle">Update your messaging or email gateway settings</div>
    </div>
    <div class="card-body">
      <form action="{{ route('api-configs.update', $apiConfig->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        <div class="form-group">
          <label class="form-label">Provider Name *</label>
          <input type="text" name="name" class="form-control" value="{{ old('name', $apiConfig->name) }}" required>
          @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
          <label class="form-label">Provider Type *</label>
          <select name="provider_type" class="form-control" required>
            <option value="SMS" {{ old('provider_type', $apiConfig->provider_type) == 'SMS' ? 'selected' : '' }}>SMS Gateway</option>
            <option value="Email" {{ old('provider_type', $apiConfig->provider_type) == 'Email' ? 'selected' : '' }}>Email Provider</option>
            <option value="WhatsApp" {{ old('provider_type', $apiConfig->provider_type) == 'WhatsApp' ? 'selected' : '' }}>WhatsApp API</option>
          </select>
          @error('provider_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
          <label class="form-label">API Endpoint</label>
          <input type="url" name="api_endpoint" class="form-control" value="{{ old('api_endpoint', $apiConfig->api_endpoint) }}">
          @error('api_endpoint') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">API Key / Username</label>
            <input type="text" name="api_key" class="form-control" value="{{ old('api_key', $apiConfig->api_key) }}">
            @error('api_key') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
          </div>
          <div class="form-group">
            <label class="form-label">API Secret / Password</label>
            <input type="password" name="api_secret" class="form-control" placeholder="Leave blank to keep current">
            @error('api_secret') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Sender ID / From Email</label>
          <input type="text" name="sender_id" class="form-control" value="{{ old('sender_id', $apiConfig->sender_id) }}">
          @error('sender_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $apiConfig->is_active) ? 'checked' : '' }} class="rounded">
            <span class="text-sm font-medium">Set as Active Gateway</span>
          </label>
          <p class="text-[10px] text-muted mt-1">Activating this will deactivate other gateways of the same type.</p>
        </div>

        <div class="flex gap-3 pt-4">
          <a href="{{ route('api-configs.index') }}" class="btn btn-secondary flex-1 text-center">Cancel</a>
          <button type="submit" class="btn btn-primary flex-1">Update Configuration</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
