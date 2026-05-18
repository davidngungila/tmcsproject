@extends('layouts.app')

@section('title', 'Finance Settings - TmcsSmart')
@section('page-title', 'Finance Settings')
@section('breadcrumb', 'TmcsSmart / Finance / Settings')

@section('content')
<div class="animate-in space-y-6">
    <!-- TABS NAVIGATION -->
    <div class="flex items-center gap-1 bg-muted/5 p-1 rounded-2xl w-fit">
        <button onclick="switchTab('general')" id="tab-general" class="tab-btn active px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all">General Settings</button>
        <button onclick="switchTab('accounts')" id="tab-accounts" class="tab-btn px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all">Bank Accounts</button>
        <button onclick="switchTab('payments')" id="tab-payments" class="tab-btn px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all">Payment Methods</button>
        <button onclick="switchTab('notifications')" id="tab-notifications" class="tab-btn px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all">Notifications</button>
    </div>

    <!-- GENERAL SETTINGS -->
    <div id="section-general" class="tab-section">
        <div class="card shadow-sm border-none overflow-hidden max-w-2xl">
            <div class="card-header border-b p-6 bg-white">
                <h3 class="text-sm font-black uppercase tracking-widest text-gray-400">Global Finance Preferences</h3>
            </div>
            <div class="card-body p-8">
                <form action="{{ route('finance.settings.update') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="form-group">
                        <label class="form-label text-xs font-black uppercase tracking-widest text-gray-400 mb-2 block">Default Currency</label>
                        <select name="settings[default_currency]" class="form-control select-modern">
                            <option value="TZS" {{ $settings['default_currency'] == 'TZS' ? 'selected' : '' }}>Tanzanian Shilling (TZS)</option>
                            <option value="USD" {{ $settings['default_currency'] == 'USD' ? 'selected' : '' }}>US Dollar (USD)</option>
                            <option value="KES" {{ $settings['default_currency'] == 'KES' ? 'selected' : '' }}>Kenyan Shilling (KES)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label text-xs font-black uppercase tracking-widest text-gray-400 mb-2 block">Receipt Footer Text</label>
                        <textarea name="settings[receipt_footer_text]" rows="3" class="form-control" placeholder="Enter text to appear at the bottom of receipts...">{{ $settings['receipt_footer_text'] }}</textarea>
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="btn btn-primary px-10 py-4 rounded-2xl shadow-lg shadow-green-200 font-black uppercase tracking-widest">Save General Settings</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- BANK ACCOUNTS -->
    <div id="section-accounts" class="tab-section hidden">
        <div class="card shadow-sm border-none overflow-hidden">
            <div class="card-header border-b p-6 bg-white flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-black uppercase tracking-widest text-gray-400">Manage Bank Accounts</h3>
                    <p class="text-[10px] text-muted font-bold uppercase tracking-widest mt-1">Configure your organization's bank connections</p>
                </div>
                <a href="{{ route('accounts.create') }}?type=Asset" class="btn btn-primary btn-sm px-6">Add New Account</a>
            </div>
            <div class="table-wrap">
                <table class="w-full">
                    <thead class="bg-gray-50/50 border-b">
                        <tr>
                            <th class="p-4 text-[10px] font-black uppercase tracking-widest text-gray-400">Account Name</th>
                            <th class="p-4 text-[10px] font-black uppercase tracking-widest text-gray-400">Account Number</th>
                            <th class="p-4 text-[10px] font-black uppercase tracking-widest text-gray-400 text-center">Current Balance</th>
                            <th class="p-4 text-[10px] font-black uppercase tracking-widest text-gray-400 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($bankAccounts as $account)
                        <tr class="hover:bg-light/30 transition-all">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex-center font-black text-xs border border-blue-100 shadow-sm">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M3 10h18M7 15h1m4 0h1m4 0h1m-7 4h12a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div class="font-black text-sm text-gray-800">{{ $account->name }}</div>
                                </div>
                            </td>
                            <td class="p-4 text-xs font-bold text-gray-500">{{ $account->code }}</td>
                            <td class="p-4 text-center font-black text-sm text-gray-800">{{ number_format($account->balance, 2) }}</td>
                            <td class="p-4 text-right">
                                <a href="{{ route('accounts.edit', $account->id) }}" class="p-2 rounded-lg text-muted hover:text-primary transition-all inline-block">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-12 text-center text-muted font-bold uppercase tracking-widest text-[10px]">No bank accounts configured.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- PAYMENT METHODS -->
    <div id="section-payments" class="tab-section hidden">
        <div class="card shadow-sm border-none overflow-hidden">
            <div class="card-header border-b p-6 bg-white flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-black uppercase tracking-widest text-gray-400">Payment Gateway Integrations</h3>
                    <p class="text-[10px] text-muted font-bold uppercase tracking-widest mt-1">Manage mobile money and online payment providers</p>
                </div>
                <a href="{{ route('api-configs.create') }}?type=Payment" class="btn btn-primary btn-sm px-6">Add Provider</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                @forelse($paymentConfigs as $config)
                <div class="card p-6 border border-muted/10 shadow-sm relative group hover:border-green-500/30 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-green-50 text-green-600 flex-center font-black text-sm border border-green-100 shadow-sm">
                            {{ substr($config->provider_name, 0, 1) }}
                        </div>
                        <span class="badge {{ $config->is_active ? 'green' : 'red' }} uppercase text-[8px] font-black tracking-widest">{{ $config->is_active ? 'Active' : 'Disabled' }}</span>
                    </div>
                    <h4 class="font-black text-gray-800 mb-1 uppercase tracking-wider text-xs">{{ $config->provider_name }}</h4>
                    <p class="text-[10px] text-muted font-bold uppercase tracking-widest">{{ $config->sender_id ?? 'No Merchant ID' }}</p>
                    
                    <div class="mt-6 flex gap-2">
                        <a href="{{ route('api-configs.edit', $config->id) }}" class="btn btn-ghost btn-sm flex-1 text-[9px] font-black uppercase tracking-widest py-3">Configure</a>
                        <a href="{{ route('api-configs.show', $config->id) }}" class="btn btn-ghost btn-sm text-gray-400 py-3">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-span-full p-12 text-center text-muted font-bold uppercase tracking-widest text-[10px]">No payment methods configured.</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- NOTIFICATION PREFERENCES -->
    <div id="section-notifications" class="tab-section hidden">
        <div class="card shadow-sm border-none overflow-hidden max-w-2xl">
            <div class="card-header border-b p-6 bg-white">
                <h3 class="text-sm font-black uppercase tracking-widest text-gray-400">Finance Notifications</h3>
            </div>
            <div class="card-body p-8">
                <form action="{{ route('finance.settings.update') }}" method="POST" class="space-y-8">
                    @csrf
                    <div class="space-y-6">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100">
                            <div>
                                <h4 class="text-xs font-black text-gray-800 uppercase tracking-wider">SMS on Contribution</h4>
                                <p class="text-[10px] text-muted font-bold mt-1 uppercase tracking-widest">Send automated receipt to members via SMS</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="settings[notify_on_contribution]" value="0">
                                <input type="checkbox" name="settings[notify_on_contribution]" value="1" class="sr-only peer" {{ $settings['notify_on_contribution'] ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100">
                            <div>
                                <h4 class="text-xs font-black text-gray-800 uppercase tracking-wider">SMS on Expense</h4>
                                <p class="text-[10px] text-muted font-bold mt-1 uppercase tracking-widest">Notify authorized persons when an expense is recorded</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="settings[notify_on_expense]" value="0">
                                <input type="checkbox" name="settings[notify_on_expense]" value="1" class="sr-only peer" {{ $settings['notify_on_expense'] ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                            </label>
                        </div>
                    </div>
                    
                    <div class="pt-4">
                        <button type="submit" class="btn btn-primary px-10 py-4 rounded-2xl shadow-lg shadow-green-200 font-black uppercase tracking-widest">Update Preferences</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function switchTab(tabId) {
    // Update tab buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active', 'bg-white', 'shadow-sm', 'text-primary');
        btn.classList.add('text-muted');
    });
    
    const activeBtn = document.getElementById('tab-' + tabId);
    activeBtn.classList.add('active', 'bg-white', 'shadow-sm', 'text-primary');
    activeBtn.classList.remove('text-muted');

    // Update sections
    document.querySelectorAll('.tab-section').forEach(section => {
        section.classList.add('hidden');
    });
    document.getElementById('section-' + tabId).classList.remove('hidden');

    // Save active tab to localStorage
    localStorage.setItem('finance_settings_tab', tabId);
}

document.addEventListener('DOMContentLoaded', function() {
    const savedTab = localStorage.getItem('finance_settings_tab') || 'general';
    switchTab(savedTab);
});
</script>
<style>
.tab-btn.active {
    background-color: white;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    color: var(--primary);
}
</style>
@endpush
@endsection
