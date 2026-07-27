<div class="space-y-4">
  <div>
    <label class="text-sm font-semibold text-gray-500">Subject</label>
    <div class="text-lg">{{ $communication->subject }}</div>
  </div>
  
  <div>
    <label class="text-sm font-semibold text-gray-500">Message</label>
    <div class="mt-1 p-3 bg-gray-50 rounded-lg">{!! $communication->message !!}</div>
  </div>
  
  <div class="grid grid-cols-2 gap-4">
    <div>
      <label class="text-sm font-semibold text-gray-500">Type</label>
      <div class="text-sm">{{ ucfirst($communication->type) }}</div>
    </div>
    <div>
      <label class="text-sm font-semibold text-gray-500">Status</label>
      <div class="text-sm">{{ ucfirst($communication->status) }}</div>
    </div>
  </div>
  
  <div>
    <label class="text-sm font-semibold text-gray-500">Recipients</label>
    <div class="text-sm">{{ count(json_decode($communication->recipients, true) ?? []) }} recipients</div>
  </div>
  
  <div class="grid grid-cols-2 gap-4">
    <div>
      <label class="text-sm font-semibold text-gray-500">Sent By</label>
      <div class="text-sm">{{ $communication->sentBy->name ?? 'N/A' }}</div>
    </div>
    <div>
      <label class="text-sm font-semibold text-gray-500">Sent At</label>
      <div class="text-sm">{{ $communication->sent_at ? $communication->sent_at->format('M d, Y H:i') : 'N/A' }}</div>
    </div>
  </div>
</div>
