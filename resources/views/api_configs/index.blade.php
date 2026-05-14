@extends('layouts.app')

@section('title', 'API Configurations - TmcsSmart')
@section('page-title', 'API Configuration')
@section('breadcrumb', 'TmcsSmart / Settings / API Config')

@section('content')
<div class="animate-in">
  <div class="flex items-center justify-between mb-6">
    <div>
      <h2 class="text-lg font-bold">API Configurations</h2>
      <p class="text-sm text-muted mt-1">Manage SMS, Email, and WhatsApp API gateways</p>
    </div>
    <a href="{{ route('api-configs.create') }}" class="btn btn-primary">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
      Add Configuration
    </a>
  </div>

  @if(session('success'))
    <div class="p-4 bg-green-50 border border-green-100 text-green-700 rounded-xl text-sm font-medium mb-6 animate-in slide-in-from-top duration-300">
        {{ session('success') }}
    </div>
  @endif

  @if(session('error'))
    <div class="p-4 bg-red-50 border border-red-100 text-red-700 rounded-xl text-sm font-medium mb-6 animate-in slide-in-from-top duration-300">
        {{ session('error') }}
    </div>
  @endif

  <div class="card overflow-hidden">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="w-full">
          <thead>
            <tr class="text-left border-b border-light bg-light/30">
              <th class="p-4 text-xs font-bold text-muted uppercase">Provider Name</th>
              <th class="p-4 text-xs font-bold text-muted uppercase">Type</th>
              <th class="p-4 text-xs font-bold text-muted uppercase">Sender ID</th>
              <th class="p-4 text-xs font-bold text-muted uppercase">Status</th>
              <th class="p-4 text-xs font-bold text-muted uppercase text-right">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($configs as $config)
            <tr class="border-b border-light hover:bg-light/30 transition-all">
              <td class="p-4">
                <div class="font-bold text-sm">{{ $config->name }}</div>
                <div class="text-[10px] text-muted truncate max-w-[200px]">{{ $config->api_endpoint }}</div>
              </td>
              <td class="p-4">
                <span class="badge {{ $config->provider_type == 'SMS' ? 'blue' : ($config->provider_type == 'Email' ? 'gold' : 'green') }}">
                  {{ $config->provider_type }}
                </span>
              </td>
              <td class="p-4 text-sm font-medium">{{ $config->sender_id ?? 'N/A' }}</td>
              <td class="p-4">
                <span class="badge {{ $config->is_active ? 'green' : 'red' }}">
                  {{ $config->is_active ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class="p-4">
                <div class="flex items-center justify-end gap-2">
                  <form action="{{ route('api-configs.test', $config->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-secondary btn-sm p-1.5" title="Test Connection">
                      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </button>
                  </form>
                  <a href="{{ route('api-configs.show', $config->id) }}" class="btn btn-secondary btn-sm p-1.5" title="View Details">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                  </a>
                  <a href="{{ route('api-configs.edit', $config->id) }}" class="btn btn-secondary btn-sm p-1.5" title="Edit Config">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                  </a>
                  <form action="{{ route('api-configs.destroy', $config->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this configuration?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-secondary btn-sm p-1.5 text-red-500" title="Delete">
                      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="5" class="p-12 text-center text-muted">
                <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 12px;display:block;">
                  <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
                <p>No configurations found</p>
                <a href="{{ route('api-configs.create') }}" class="btn btn-primary mt-4">Add First Configuration</a>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="mt-4">
    {{ $configs->links() }}
  </div>
</div>
@endsection
