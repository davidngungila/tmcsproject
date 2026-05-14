@extends('layouts.app')

@section('title', 'System Settings - TmcsSmart')
@section('page-title', 'System Settings')
@section('breadcrumb', 'TmcsSmart / Administration / Settings')

@section('content')
<div class="animate-in">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- LEFT COLUMN: NAVIGATION -->
        <div class="lg:col-span-1 space-y-4">
            <div class="card">
                <div class="card-body p-0">
                    <div class="flex flex-col">
                        <a href="#general" class="flex items-center gap-3 p-4 border-b border-light hover:bg-light transition-all text-green-600 font-bold">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
                            General Settings
                        </a>
                        <a href="#localization" class="flex items-center gap-3 p-4 border-b border-light hover:bg-light transition-all text-muted">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Localization
                        </a>
                        <a href="#notifications" class="flex items-center gap-3 p-4 border-b border-light hover:bg-light transition-all text-muted">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            Notifications
                        </a>
                        <a href="#email" class="flex items-center gap-3 p-4 border-b border-light hover:bg-light transition-all text-muted">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            Email Configuration
                        </a>
                        <a href="#backup" class="flex items-center gap-3 p-4 hover:bg-light transition-all text-muted">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/></svg>
                            Backup & Maintenance
                        </a>
                    </div>
                </div>
            </div>

            <div class="card bg-green-900 text-white">
                <div class="card-body">
                    <h4 class="font-bold mb-2">Need Help?</h4>
                    <p class="text-xs opacity-80 mb-4">If you are unsure about any setting, please contact the system administrator or refer to the documentation.</p>
                    <button class="btn btn-primary btn-sm w-full bg-white text-green-900 hover:bg-green-50">View Documentation</button>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: CONTENT -->
        <div class="lg:col-span-2 space-y-6">
            <!-- GENERAL SETTINGS -->
            <div class="card" id="general">
                <div class="card-header">
                    <h3 class="card-title">General Settings</h3>
                    <p class="card-subtitle">Basic system identification and branding</p>
                </div>
                <div class="card-body">
                    <form action="#" method="POST" class="space-y-4">
                        <div class="form-group">
                            <label class="form-label">System Name</label>
                            <input type="text" class="form-control" value="TmcsSmart - Church Management System">
                        </div>
                        <div class="form-group">
                            <label class="form-label">System Email</label>
                            <input type="email" class="form-control" value="noreply@tmcssmart.com">
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Church Name</label>
                                <input type="text" class="form-control" value="St. Peters Catholic Church">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Parish/Diocese</label>
                                <input type="text" class="form-control" value="Dar es Salaam Diocese">
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="btn btn-primary">Save General Settings</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- LOCALIZATION -->
            <div class="card" id="localization">
                <div class="card-header">
                    <h3 class="card-title">Localization</h3>
                    <p class="card-subtitle">Manage timezones, currency and languages</p>
                </div>
                <div class="card-body">
                    <form action="#" method="POST" class="space-y-4">
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">System Language</label>
                                <select class="form-control">
                                    <option selected>English (United States)</option>
                                    <option>Swahili (Tanzania)</option>
                                    <option>French (France)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Timezone</label>
                                <select class="form-control">
                                    <option selected>(GMT+03:00) East Africa Time</option>
                                    <option>(GMT+00:00) UTC</option>
                                    <option>(GMT-05:00) Eastern Time</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Currency Symbol</label>
                                <input type="text" class="form-control" value="TZS">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Date Format</label>
                                <select class="form-control">
                                    <option selected>MMM dd, YYYY (May 14, 2026)</option>
                                    <option>DD/MM/YYYY (14/05/2026)</option>
                                    <option>MM/DD/YYYY (05/14/2026)</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="btn btn-primary">Save Localization</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- SYSTEM STATUS -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">System Information</h3>
                </div>
                <div class="card-body p-0">
                    <table class="w-full">
                        <tbody>
                            <tr class="border-b border-light">
                                <td class="p-4 text-xs font-bold text-muted uppercase">Version</td>
                                <td class="p-4 text-sm">v1.2.5 (Stable)</td>
                            </tr>
                            <tr class="border-b border-light">
                                <td class="p-4 text-xs font-bold text-muted uppercase">Laravel Version</td>
                                <td class="p-4 text-sm">11.0.0</td>
                            </tr>
                            <tr class="border-b border-light">
                                <td class="p-4 text-xs font-bold text-muted uppercase">PHP Version</td>
                                <td class="p-4 text-sm">8.4.12</td>
                            </tr>
                            <tr>
                                <td class="p-4 text-xs font-bold text-muted uppercase">Environment</td>
                                <td class="p-4 text-sm">
                                    <span class="badge green">Production</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
