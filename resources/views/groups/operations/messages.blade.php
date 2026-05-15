@extends('layouts.app')

@section('title', 'Group Messaging - ' . $group->name)
@section('page-title', 'Group Messaging & Templates: ' . $group->name)
@section('breadcrumb', 'Home / Group / Operations / Messages')

@section('content')
<div class="animate-in space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- LEFT: SEND & SCHEDULE -->
        <div class="lg:col-span-7 space-y-6">
            <!-- BROADCAST CARD -->
            <div class="card shadow-lg border-green-100">
                <div class="card-header border-b bg-green-50/30 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-green-600 text-white flex-center">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <h3 class="card-title text-green-900 font-black text-xs uppercase tracking-widest">Broadcast SMS</h3>
                    </div>
                    <span class="text-[10px] font-black text-green-600 uppercase tracking-widest">{{ $group->members()->whereNotNull('phone')->count() }} Recipients</span>
                </div>
                <div class="card-body p-6">
                    <form action="{{ route('groups.operations.messages.send', $group->id) }}" method="POST" id="broadcastForm">
                        @csrf
                        
                        <div class="form-group mb-4">
                            <label class="form-label text-[10px] font-black uppercase text-gray-400 tracking-widest mb-2 block">Quick Template Selection</label>
                            <select class="form-control rounded-xl border-gray-200 text-xs font-bold p-3" onchange="applyTemplate(this.value); this.value=''">
                                <option value="">-- Choose a template to use --</option>
                                @foreach($templates as $template)
                                <option value="{{ $template->content }}">{{ $template->name }} {{ $template->is_global ? '(Global)' : '' }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label text-[10px] font-black uppercase text-gray-400 tracking-widest mb-2 block">Message Body</label>
                            <textarea name="message" id="messageBox" class="form-control text-sm p-4 rounded-xl border-gray-200 focus:ring-green-500 focus:border-green-500" 
                                      rows="4" placeholder="Type your community message here..." required maxlength="160"></textarea>
                            <div class="flex justify-between mt-2">
                                <p class="text-[9px] text-muted font-bold uppercase tracking-widest">Only text values • Max 160 characters</p>
                                <p class="text-[10px] font-black uppercase tracking-widest"><span id="charCount" class="text-green-600">0</span>/160</p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <button type="submit" class="btn btn-primary flex-1 py-3 font-black uppercase tracking-[0.2em] text-[10px] shadow-green-100 shadow-lg">
                                Send Now
                            </button>
                            <button type="button" class="btn btn-secondary px-6 font-black uppercase text-[10px] tracking-widest" onclick="toggleScheduleSection()">
                                Schedule for Later
                            </button>
                        </div>
                    </form>

                    <!-- HIDDEN SCHEDULE FORM -->
                    <div id="scheduleSection" class="hidden mt-6 pt-6 border-t border-dashed border-gray-100 animate-in">
                        <form action="{{ route('groups.operations.messages.schedule', $group->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="message" id="scheduledMessageHidden">
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div class="form-group">
                                    <label class="form-label text-[10px] font-black uppercase text-gray-400 tracking-widest mb-2 block">Task Title</label>
                                    <input type="text" name="title" class="form-control rounded-xl border-gray-200" placeholder="e.g. Weekly Meeting Reminder" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label text-[10px] font-black uppercase text-gray-400 tracking-widest mb-2 block">Auto Reschedule (Frequency)</label>
                                    <select name="frequency" class="form-control rounded-xl border-gray-200">
                                        <option value="once">Send Once</option>
                                        <option value="weekly">Every Week</option>
                                        <option value="monthly">Every Month</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group mb-6">
                                <label class="form-label text-[10px] font-black uppercase text-gray-400 tracking-widest mb-2 block">Execution Date & Time</label>
                                <input type="datetime-local" name="scheduled_at" class="form-control rounded-xl border-gray-200" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-full py-3 font-black uppercase tracking-[0.2em] text-[10px]">
                                Confirm Scheduled Task
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- SCHEDULED LIST -->
            <div class="card border-none shadow-sm">
                <div class="card-header border-b p-6 bg-gray-50/50">
                    <h3 class="card-title text-xs font-black uppercase tracking-widest text-gray-400">Scheduled Queue</h3>
                </div>
                <div class="card-body p-0">
                    <div class="divide-y divide-gray-100">
                        @forelse($scheduledMessages as $msg)
                        <div class="p-4 hover:bg-light/30 transition-all flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex-center">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <div class="text-xs font-black text-gray-800 uppercase tracking-wider">{{ $msg->title }}</div>
                                    <div class="text-[9px] text-muted font-bold uppercase tracking-widest">
                                        {{ $msg->scheduled_at->format('M d, Y @ H:i') }} • <span class="text-amber-600">{{ $msg->frequency }}</span>
                                    </div>
                                </div>
                            </div>
                            <span class="badge {{ $msg->is_active ? 'green' : 'gray' }} uppercase text-[9px] font-black tracking-widest">{{ $msg->is_active ? 'Active' : 'Paused' }}</span>
                        </div>
                        @empty
                        <div class="p-12 text-center text-muted italic text-[10px] uppercase tracking-widest font-bold">No messages in the queue.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT: TEMPLATES -->
        <div class="lg:col-span-5 space-y-6">
            <!-- TEMPLATE MANAGEMENT -->
            <div class="card shadow-lg border-green-100">
                <div class="card-header border-b bg-green-50/30 p-6 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-green-600 text-white flex-center">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                        <div>
                            <h3 class="card-title text-green-900 font-black text-xs uppercase tracking-widest">Quick Templates</h3>
                            <p class="text-[9px] text-green-600 font-bold uppercase tracking-[0.2em]">Reusable Messages</p>
                        </div>
                    </div>
                    <button class="btn btn-ghost btn-sm text-green-600" onclick="toggleNewTemplate()">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
                    </button>
                </div>
                
                <div class="card-body p-0">
                    <!-- NEW TEMPLATE FORM (HIDDEN) -->
                    <div id="newTemplateSection" class="hidden p-6 bg-green-50 border-b border-green-100 animate-in">
                        <form action="{{ route('groups.operations.messages.templates.store', $group->id) }}" method="POST">
                            @csrf
                            <div class="form-group mb-4">
                                <label class="form-label text-[10px] font-black uppercase text-green-800 tracking-widest mb-2 block">Template Name</label>
                                <input type="text" name="name" class="form-control rounded-xl border-green-200" placeholder="e.g. Budget Notice" required>
                            </div>
                            <div class="form-group mb-4">
                                <label class="form-label text-[10px] font-black uppercase text-green-800 tracking-widest mb-2 block">Content (Max 160)</label>
                                <textarea name="content" class="form-control rounded-xl border-green-200 text-sm" rows="3" placeholder="Message content..." required maxlength="160"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-full py-3 font-black uppercase tracking-widest text-[10px]">Save Template</button>
                        </form>
                    </div>

                    <div class="divide-y divide-gray-50 max-h-[500px] overflow-y-auto">
                        @foreach($templates as $template)
                        <div class="p-6 hover:bg-green-50/10 transition-all group">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-[10px] font-black text-green-900 uppercase tracking-widest flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full {{ $template->is_global ? 'bg-amber-400' : 'bg-green-500' }}"></div>
                                    {{ $template->name }}
                                    @if($template->is_global) <span class="badge gold scale-75">Global</span> @endif
                                </span>
                                <button class="text-[10px] font-black text-green-600 uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-opacity" 
                                        onclick="applyTemplate('{{ addslashes($template->content) }}')">
                                    Apply
                                </button>
                            </div>
                            <p class="text-xs text-muted leading-relaxed">{{ $template->content }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const messageBox = document.getElementById('messageBox');
    const charCount = document.getElementById('charCount');
    const scheduledHidden = document.getElementById('scheduledMessageHidden');
    const scheduleSection = document.getElementById('scheduleSection');
    const newTemplateSection = document.getElementById('newTemplateSection');

    messageBox.addEventListener('input', () => {
        // Filter only text values (strip HTML if any, though it's a textarea)
        const filtered = messageBox.value.replace(/[^\x20-\x7E\n]/g, '');
        if (messageBox.value !== filtered) {
            messageBox.value = filtered;
        }
        
        charCount.textContent = messageBox.value.length;
        scheduledHidden.value = messageBox.value;
    });

    function applyTemplate(content) {
        messageBox.value = content;
        charCount.textContent = content.length;
        scheduledHidden.value = content;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function toggleScheduleSection() {
        if (!messageBox.value) {
            alert('Please type a message first before scheduling.');
            return;
        }
        scheduleSection.classList.toggle('hidden');
        scheduledHidden.value = messageBox.value;
    }

    function toggleNewTemplate() {
        newTemplateSection.classList.toggle('hidden');
    }
</script>
@endsection
