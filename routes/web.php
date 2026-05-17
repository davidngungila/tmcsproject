<?php

use App\Http\Controllers\ContributionTypeController;
use App\Http\Controllers\MessageTemplateController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\MemberCategoryController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\GroupOperationController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\CommunicationController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\ElectionController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ApiConfigController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReconciliationController;
use App\Http\Controllers\FinancialReportController;
use App\Http\Controllers\FinancialStatementController;
use App\Http\Controllers\Member\ProfileController as MemberProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use App\Http\Controllers\ActivityMonitoringController;
use App\Http\Controllers\RoleManagementController;
use App\Http\Controllers\SecurityController;
use App\Http\Controllers\SystemSettingsController;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Forgot Password Routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetPasswordEmail'])->name('password.email');

// Root redirect to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Webhooks
Route::post('/webhooks/snipe', [WebhookController::class, 'handleSnipe']);

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // User Management
    Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::resource('users', UserController::class);

    // Admin Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // System Settings & Monitoring
    Route::prefix('settings')->name('settings.')->group(function () {
        // Activity Monitoring
        Route::get('/monitoring', [ActivityMonitoringController::class, 'dashboard'])->name('monitoring.dashboard');
        Route::get('/monitoring/auth-logs', [ActivityMonitoringController::class, 'authLogs'])->name('monitoring.auth-logs');
        Route::get('/monitoring/action-logs', [ActivityMonitoringController::class, 'actionLogs'])->name('monitoring.action-logs');

        // Security Controls
        Route::get('/security', [SecurityController::class, 'index'])->name('security.index');
        Route::post('/security/update', [SecurityController::class, 'update'])->name('security.update');
        Route::post('/security/force-logout-all', [SecurityController::class, 'forceLogoutAll'])->name('security.force-logout-all');
        Route::post('/security/block-ip', [SecurityController::class, 'blockIp'])->name('security.block-ip');

        // Role Management
        Route::get('/roles', [RoleManagementController::class, 'index'])->name('roles.index');
        Route::post('/roles', [RoleManagementController::class, 'store'])->name('roles.store');
        Route::put('/roles/{role}', [RoleManagementController::class, 'update'])->name('roles.update');
        Route::post('/roles/{role}/clone', [RoleManagementController::class, 'clone'])->name('roles.clone');
        Route::delete('/roles/{role}', [RoleManagementController::class, 'destroy'])->name('roles.destroy');

        // Profile Settings (Restored from previous version)
        Route::post('/profile/update', [SettingsController::class, 'updateProfile'])->name('profile.update');

        // General Settings
        Route::get('/', [SystemSettingsController::class, 'index'])->name('index');
        Route::post('/update', [SystemSettingsController::class, 'update'])->name('update');
    });

    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

    // Members
    Route::get('/members/import-template', [MemberController::class, 'downloadTemplate'])->name('members.template');
    Route::post('/members/import', [MemberController::class, 'import'])->name('members.import');
    Route::resource('members/categories', MemberCategoryController::class)->names([
        'index' => 'members.categories',
        'create' => 'members.categories.create',
        'store' => 'members.categories.store',
        'show' => 'members.categories.show',
        'edit' => 'members.categories.edit',
        'update' => 'members.categories.update',
        'destroy' => 'members.categories.destroy',
    ]);
    Route::get('/members/{member}/id-card', [MemberController::class, 'idCard'])->name('members.id-card');
    Route::post('/members/{member}/approve', [MemberController::class, 'approve'])->name('members.approve');
    Route::resource('members', MemberController::class)->except(['categories']);

    // Finance
    Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');
    Route::get('/finance/reports', [FinancialReportController::class, 'index'])->name('finance.reports');
    Route::get('/finance/reports/income-statement', [FinancialStatementController::class, 'incomeStatement'])->name('finance.reports.income_statement');
    Route::get('/finance/reports/balance-sheet', [FinancialStatementController::class, 'balanceSheet'])->name('finance.reports.balance_sheet');
    Route::get('/finance/settings', [FinanceController::class, 'settings'])->name('finance.settings');
    Route::get('/finance/{contribution}/receipt', [FinanceController::class, 'receipt'])->name('finance.receipt');
    Route::post('/finance/{finance}/verify', [FinanceController::class, 'verify'])->name('finance.verify');
    Route::resource('finance/types', ContributionTypeController::class)->names('finance.types');
    Route::resource('finance', FinanceController::class);
    Route::post('/expenses/{expense}/approve', [ExpenseController::class, 'approve'])->name('expenses.approve');
    Route::post('/expenses/{expense}/reject', [ExpenseController::class, 'reject'])->name('expenses.reject');
    Route::resource('expenses', ExpenseController::class);
    Route::resource('reconciliation', ReconciliationController::class);

    // Groups
    Route::get('/groups/communities', [GroupController::class, 'communities'])->name('groups.communities');
    Route::get('/groups/activities', [GroupController::class, 'activities'])->name('groups.activities');
    Route::post('/groups/{group}/assign-leadership', [GroupController::class, 'assignLeadership'])->name('groups.assign-leadership');
    Route::get('/groups/{group}/reports', [GroupController::class, 'reports'])->name('groups.reports.index');
    Route::get('/groups/{group}/reports/{type}', [GroupController::class, 'viewReport'])->name('groups.reports.view');
    Route::resource('groups', GroupController::class);

    // Group Operations (for Leaders)
    Route::prefix('groups/{group}/operations')->name('groups.operations.')->group(function () {
        Route::get('/members', [GroupOperationController::class, 'members'])->name('members');
        Route::post('/members/add', [GroupOperationController::class, 'addMember'])->name('members.add');
        Route::delete('/members/{member}/remove', [GroupOperationController::class, 'removeMember'])->name('members.remove');
        Route::get('/contributions', [GroupOperationController::class, 'contributions'])->name('contributions');
        Route::post('/contributions', [GroupOperationController::class, 'storeContribution'])->name('contributions.store');
        Route::get('/attendance', [GroupOperationController::class, 'attendance'])->name('attendance');
        Route::post('/attendance', [GroupOperationController::class, 'storeAttendance'])->name('attendance.store');
        Route::get('/meeting/{meeting}', [GroupOperationController::class, 'showMeeting'])->name('meeting.show');
        Route::get('/planning', [GroupOperationController::class, 'planning'])->name('planning');
        Route::post('/planning', [GroupOperationController::class, 'storePlan'])->name('planning.store');
        Route::get('/messages', [GroupOperationController::class, 'messages'])->name('messages');
        Route::post('/messages', [GroupOperationController::class, 'sendMessage'])->name('messages.send');
        Route::post('/messages/templates', [GroupOperationController::class, 'storeTemplate'])->name('messages.templates.store');
        Route::post('/messages/schedule', [GroupOperationController::class, 'scheduleMessage'])->name('messages.schedule');
    });

    // Communications
    Route::get('/communications/announcements', [CommunicationController::class, 'announcements'])->name('communications.announcements');
    Route::resource('communications', CommunicationController::class);
    Route::post('/message-templates/test', [MessageTemplateController::class, 'test'])->name('message-templates.test');
    Route::resource('message-templates', MessageTemplateController::class);
    Route::post('/api-configs/{api_config}/test', [ApiConfigController::class, 'testConnection'])->name('api-configs.test');
    Route::get('/api-configs/{api_config}/balance', [ApiConfigController::class, 'getBalance'])->name('api-configs.balance');
    Route::resource('api-configs', ApiConfigController::class);

    // Events
    Route::get('/events/attendance', [EventController::class, 'attendance'])->name('events.attendance');
    Route::resource('events', EventController::class);

    // Assets
    Route::get('/assets/maintenance', [AssetController::class, 'maintenance'])->name('assets.maintenance');
    Route::get('/assets/assignments', [AssetController::class, 'assignments'])->name('assets.assignments');
    Route::resource('assets', AssetController::class);

    // Shop (POS)
    Route::get('/shop/sales', [ShopController::class, 'salesHistory'])->name('shop.sales');
    Route::get('/shop/create-sale', [ShopController::class, 'createSale'])->name('shop.create-sale');
    Route::post('/shop/store-sale', [ShopController::class, 'storeSale'])->name('shop.store-sale');
    Route::resource('shop', ShopController::class);

    // Certificates
    Route::get('/certificates/verify', [CertificateController::class, 'verify'])->name('certificates.verify');
    Route::resource('certificates', CertificateController::class);

    // Member Self-Service Routes
    Route::prefix('member')->name('member.')->group(function () {
        Route::get('/profile', [MemberProfileController::class, 'index'])->name('profile.index');
        Route::get('/profile/edit', [MemberProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/profile/update', [MemberProfileController::class, 'update'])->name('profile.update');
        Route::get('/pay', [MemberProfileController::class, 'pay'])->name('profile.pay');
        Route::post('/pay', [MemberProfileController::class, 'processPayment'])->name('profile.process-payment');
        Route::get('/payment-status/{contribution}', [MemberProfileController::class, 'checkStatus'])->name('profile.payment-status');
        
        // New specific member portal views
        Route::get('/communities', [MemberProfileController::class, 'communities'])->name('communities');
        Route::get('/groups', [MemberProfileController::class, 'groups'])->name('groups');
        Route::get('/contributions', [MemberProfileController::class, 'contributions'])->name('contributions.index');
        Route::get('/contributions/{contribution}', [MemberProfileController::class, 'contributionShow'])->name('contributions.show');
        Route::get('/events', [MemberProfileController::class, 'events'])->name('events');
        Route::post('/groups/{group}/join', [MemberProfileController::class, 'joinGroup'])->name('groups.join');
        Route::post('/groups/{group}/leave', [MemberProfileController::class, 'leaveGroup'])->name('groups.leave');
        Route::get('/id-card', [MemberProfileController::class, 'idCard'])->name('id-card');
    });

    // Elections
    Route::get('/elections/results', [ElectionController::class, 'results'])->name('elections.results');
    Route::get('/elections/{election}/vote', [ElectionController::class, 'vote'])->name('elections.vote');
    Route::resource('elections', ElectionController::class);
});
