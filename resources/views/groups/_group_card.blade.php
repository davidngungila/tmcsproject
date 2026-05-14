<div class="card hover:shadow-xl transition-all duration-300 border border-gray-200 rounded-xl overflow-hidden">
  <div class="card-body p-5">
    <!-- Header with Avatar and Status -->
    <div class="flex items-start justify-between mb-3">
      <div class="flex items-center gap-3">
        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center text-white font-bold text-lg shadow-md">
          {{ substr($group->name, 0, 2) }}
        </div>
        <div>
          <h3 class="font-bold text-base text-gray-800">{{ $group->name }}</h3>
          <span class="badge {{ $group->is_active ? 'green' : 'red' }} text-xs mt-1">
            {{ $group->is_active ? 'Active' : 'Inactive' }}
          </span>
        </div>
      </div>
    </div>
    
    <!-- Description -->
    <p class="text-sm text-gray-600 mb-3 leading-relaxed">{{ $group->description }}</p>
    
    <!-- Group Details -->
    <div class="bg-gray-50 rounded-lg p-3 mb-3 border border-gray-100">
      <div class="grid grid-cols-2 gap-2 text-xs">
        <div class="flex items-center gap-2">
          <span class="text-gray-500">Type:</span>
          <span class="badge blue text-xs">{{ ucfirst($group->type) }}</span>
        </div>
        <div class="flex items-center gap-2">
          <span class="text-gray-500">Members:</span>
          <span class="font-semibold text-green-600">{{ $group->members->count() }}</span>
        </div>
        <div class="flex items-center gap-2 col-span-2">
          <span class="text-gray-500">Leader:</span>
          <span class="font-medium">{{ $group->leader ? $group->leader->full_name : 'Not assigned' }}</span>
        </div>
        <div class="flex items-center gap-2 col-span-2">
          <span class="text-gray-500">Formed:</span>
          <span class="font-medium">{{ $group->formation_date->format('M d, Y') }}</span>
        </div>
      </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="flex gap-2">
      <button class="btn btn-ghost btn-sm flex-1 hover:bg-green-50 hover:text-green-600 transition-colors" onclick="viewGroup({{ $group->id }})">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
        View
      </button>
      @if(auth()->user()->hasPermission('groups.edit'))
      <a href="{{ route('groups.edit', $group->id) }}" class="btn btn-ghost btn-sm flex-1 hover:bg-blue-50 hover:text-blue-600 transition-colors">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        Edit
      </a>
      @endif
    </div>
  </div>
</div>