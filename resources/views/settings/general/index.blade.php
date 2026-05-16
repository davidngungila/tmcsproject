@extends('layouts.app')

@section('title', 'General Settings - TmcsSmart')
@section('page-title', 'System Configuration')
@section('breadcrumb', 'TmcsSmart / Settings / General')

@section('content')
<div class="animate-in max-w-4xl mx-auto">
  <div class="card shadow-sm overflow-hidden">
    <div class="card-header border-b p-6 bg-muted/5">
      <h3 class="font-bold text-lg">General System Configuration</h3>
      <p class="text-xs text-muted mt-1">Global settings for application behavior and identification.</p>
    </div>
    <div class="card-body p-6">
      @if($settings->count() > 0)
      <form action="{{ route('settings.update') }}" method="POST" class="space-y-6">
        @csrf
        
        @foreach($settings as $setting)
        <div class="form-group">
          <label class="text-[10px] font-black uppercase tracking-widest text-muted block mb-2">{{ $setting->display_name }}</label>
          
          @if($setting->type == 'boolean')
          <select name="settings[{{ $setting->key }}]" class="form-control">
            <option value="1" {{ $setting->value == '1' ? 'selected' : '' }}>Enabled</option>
            <option value="0" {{ $setting->value == '0' ? 'selected' : '' }}>Disabled</option>
          </select>
          @elseif($setting->type == 'integer')
          <input type="number" name="settings[{{ $setting->key }}]" class="form-control" value="{{ $setting->value }}">
          @else
          <input type="text" name="settings[{{ $setting->key }}]" class="form-control" value="{{ $setting->value }}">
          @endif
          
          @if($setting->help_text)
          <p class="text-[10px] text-muted mt-1">{{ $setting->help_text }}</p>
          @endif
        </div>
        @endforeach

        <div class="flex justify-end pt-6 border-t border-dashed">
          <button type="submit" class="btn btn-primary px-10">Save Configuration</button>
        </div>
      </form>
      @else
      <div class="text-center py-12">
        <div class="w-16 h-16 bg-muted/10 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
        </div>
        <h4 class="font-bold text-muted">No settings available</h4>
        <p class="text-xs text-muted mt-1">General system settings haven't been seeded or configured yet.</p>
      </div>
      @endif
    </div>
  </div>
</div>
@endsection
