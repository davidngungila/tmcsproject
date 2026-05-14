@extends('layouts.app')

@section('title', 'API Config Details - TmcsSmart')
@section('page-title', 'API Config Details')
@section('breadcrumb', 'TmcsSmart / Settings / API Config / View')

@section('content')
<div class="animate-in space-y-6">
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Configuration Card -->
    <div class="lg:col-span-2 space-y-6">
      <div class="card">
        <div class="card-header flex items-center justify-between">
          <div>
            <div class="card-title text-xl">{{ $apiConfig->name }}</div>
            <div class="card-subtitle">Detailed Gateway Configuration</div>
          </div>
          <div class="flex items-center gap-2">
            <span class="badge {{ $apiConfig->is_active ? 'green' : 'red' }} py-1 px-3">
              {{ $apiConfig->is_active ? 'Active' : 'Inactive' }}
            </span>
          </div>
        </div>
        <div class="card-body">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
            <div class="space-y-1">
              <label class="text-[10px] text-muted uppercase font-bold tracking-wider">Provider Type</label>
              <div class="flex items-center gap-2">
                @if($apiConfig->provider_type === 'SMS')
                  <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                  </div>
                @elseif($apiConfig->provider_type === 'Payment')
                  <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center text-green-600">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                  </div>
                @else
                  <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center text-purple-600">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                  </div>
                @endif
                <p class="text-sm font-semibold">{{ $apiConfig->provider_type }}</p>
              </div>
            </div>

            <div class="space-y-1">
              <label class="text-[10px] text-muted uppercase font-bold tracking-wider">Sender ID / From</label>
              <p class="text-sm font-semibold flex items-center gap-2">
                <span class="px-2 py-0.5 bg-gray-100 rounded border border-gray-200">{{ $apiConfig->sender_id ?? 'Not set' }}</span>
              </p>
            </div>

            <div class="md:col-span-2 space-y-1 mt-2">
              <label class="text-[10px] text-muted uppercase font-bold tracking-wider">API Endpoint</label>
              <div class="flex items-center gap-2 bg-light p-2 rounded border border-gray-100 group">
                <code class="text-xs text-blue-600 break-all flex-1">{{ $apiConfig->api_endpoint ?? 'Not set' }}</code>
                <button onclick="navigator.clipboard.writeText('{{ $apiConfig->api_endpoint }}')" class="opacity-0 group-hover:opacity-100 p-1 hover:bg-white rounded transition-all" title="Copy URL">
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/></svg>
                </button>
              </div>
            </div>

            <div class="md:col-span-2 space-y-1 mt-2">
              <label class="text-[10px] text-muted uppercase font-bold tracking-wider">API Key / Token</label>
              <div class="flex items-center gap-2 bg-light p-2 rounded border border-gray-100 group">
                <code class="text-xs text-gray-700 flex-1">
                  @if($apiConfig->api_key)
                    {{ substr($apiConfig->api_key, 0, 8) }}••••••••••••••••{{ substr($apiConfig->api_key, -4) }}
                  @else
                    Not set
                  @endif
                </code>
                <button class="opacity-0 group-hover:opacity-100 p-1 hover:bg-white rounded transition-all" title="Show Key (requires re-auth)" disabled>
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </button>
              </div>
            </div>
          </div>

          <div class="mt-8 pt-6 border-t border-gray-100 flex flex-wrap gap-3">
            <a href="{{ route('api-configs.index') }}" class="btn btn-secondary px-6">
              <svg width="16" height="16" class="mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
              Back to List
            </a>
            <a href="{{ route('api-configs.edit', $apiConfig->id) }}" class="btn btn-primary px-6">
              <svg width="16" height="16" class="mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
              Edit Configuration
            </a>
          </div>
        </div>
      </div>

      <!-- Quick Usage / Documentation -->
      <div class="card bg-gray-900 text-white">
        <div class="card-header border-gray-800">
          <div class="card-title text-white">Implementation Guide</div>
        </div>
        <div class="card-body">
          @if($apiConfig->provider_type === 'SMS')
            <div class="space-y-4">
              <p class="text-sm text-gray-400">Use the following endpoint for sending single SMS messages via Messaging Service:</p>
              <div class="bg-black/50 p-3 rounded font-mono text-[11px] overflow-x-auto">
                <span class="text-purple-400">POST</span> {{ $apiConfig->api_endpoint }}<br>
                <span class="text-blue-400">Authorization:</span> Bearer YOUR_TOKEN<br>
                <span class="text-blue-400">Content-Type:</span> application/json
              </div>
              <div class="grid grid-cols-2 gap-4">
                <div class="p-3 bg-white/5 rounded border border-white/10">
                  <div class="text-[10px] uppercase font-bold text-gray-500 mb-1">Payload Format</div>
                  <pre class="text-[10px] text-green-400">
{
  "from": "{{ $apiConfig->sender_id }}",
  "to": "255XXXXXXXXX",
  "text": "Hello Message"
}</pre>
                </div>
                <div class="p-3 bg-white/5 rounded border border-white/10">
                  <div class="text-[10px] uppercase font-bold text-gray-500 mb-1">Response Success</div>
                  <pre class="text-[10px] text-blue-400">
{
  "messages": [
    {
      "to": "255XXXXXXXXX",
      "status": "SENT",
      "messageId": "..."
    }
  ]
}</pre>
                </div>
              </div>
            </div>
          @elseif($apiConfig->provider_type === 'Payment')
            <div class="space-y-4">
              <p class="text-sm text-gray-400">Integration guide for Snippe Payment Gateway:</p>
              <div class="bg-black/50 p-3 rounded font-mono text-[11px] overflow-x-auto">
                <span class="text-purple-400">POST</span> https://api.snippe.sh/v1/payments<br>
                <span class="text-blue-400">Authorization:</span> Bearer YOUR_API_KEY<br>
                <span class="text-blue-400">Idempotency-Key:</span> unique_request_id
              </div>
              <div class="bg-white/5 p-3 rounded border border-white/10">
                <div class="text-[10px] uppercase font-bold text-gray-500 mb-2">Supported Payment Types</div>
                <div class="flex flex-wrap gap-2">
                  <span class="px-2 py-1 bg-blue-500/20 text-blue-400 rounded text-[10px]">mobile</span>
                  <span class="px-2 py-1 bg-green-500/20 text-green-400 rounded text-[10px]">card</span>
                  <span class="px-2 py-1 bg-purple-500/20 text-purple-400 rounded text-[10px]">dynamic-qr</span>
                </div>
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>

    <!-- Side Panel: Actions & Status -->
    <div class="space-y-6">
      <div class="card">
        <div class="card-header">
          <div class="card-title">Service Connectivity</div>
        </div>
        <div class="card-body space-y-6">
          <div class="p-4 bg-light rounded-xl border border-gray-100">
            <div class="flex items-center justify-between mb-4">
              <span class="text-xs font-bold uppercase text-muted">Current Status</span>
              <div class="flex items-center gap-1.5">
                <div class="w-2.5 h-2.5 rounded-full {{ $apiConfig->is_active ? 'bg-green animate-pulse' : 'bg-red' }}"></div>
                <span class="text-sm font-semibold {{ $apiConfig->is_active ? 'text-green-700' : 'text-red-700' }}">
                  {{ $apiConfig->is_active ? 'Operational' : 'Disabled' }}
                </span>
              </div>
            </div>
            
            <p class="text-[11px] text-muted leading-relaxed">
              @if($apiConfig->is_active)
                This gateway is currently <strong>active</strong> and will be used to process all outgoing {{ strtolower($apiConfig->provider_type) }} traffic.
              @else
                This gateway is <strong>inactive</strong>. It must be enabled in settings before it can be used for production traffic.
              @endif
            </p>
          </div>

          <div class="space-y-4">
            <label class="text-xs font-bold uppercase text-muted block">Connection Diagnostic</label>
            <form action="{{ route('api-configs.test', $apiConfig->id) }}" method="POST" class="space-y-3">
              @csrf
              <div class="space-y-2">
                <label class="text-[10px] font-bold text-muted uppercase">Test Phone Number</label>
                <input type="text" name="test_phone" value="0622239304" placeholder="255XXXXXXXXX" class="form-control text-sm py-2" required>
              </div>

              @if($apiConfig->provider_type === 'Payment')
              <div class="space-y-2">
                <label class="text-[10px] font-bold text-muted uppercase">Test Amount (TZS)</label>
                <input type="number" name="test_amount" value="500" class="form-control text-sm py-2" required>
              </div>
              @endif

              <button type="submit" class="btn btn-secondary w-full py-3 flex items-center justify-center gap-2 hover:bg-gray-100 transition-all border-2 border-gray-200 shadow-sm">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <span class="font-bold">Run Live Test</span>
              </button>
            </form>
            <p class="text-[10px] text-center text-muted">Performs a real-time handshake or transaction test with the provider endpoint.</p>
          </div>
        </div>
      </div>

      <!-- Statistics / Usage -->
      <div class="card">
        <div class="card-header">
          <div class="card-title">Usage Statistics</div>
        </div>
        <div class="card-body p-0">
          <div class="divide-y divide-gray-100">
            <div class="p-4 flex items-center justify-between">
              <div class="text-sm text-muted">Total Requests</div>
              <div class="text-sm font-bold">0</div>
            </div>
            <div class="p-4 flex items-center justify-between">
              <div class="text-sm text-muted">Success Rate</div>
              <div class="text-sm font-bold text-green-600">0%</div>
            </div>
            <div class="p-4 flex items-center justify-between">
              <div class="text-sm text-muted">Avg. Response</div>
              <div class="text-sm font-bold text-blue-600">0ms</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
