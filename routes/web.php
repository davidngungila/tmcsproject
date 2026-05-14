<?php

use App\Http\Controllers\MessageTemplateController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
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

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Root redirect to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Webhooks (Exempt from CSRF)
Route::post('/webhooks/snipe', [WebhookController::class, 'handleSnipe'])->name('webhooks.snipe');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Settings & Profile
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::get('/settings/security', [SettingsController::class, 'security'])->name('settings.security');
    Route::post('/settings/profile/update', [SettingsController::class, 'updateProfile'])->name('settings.profile.update');
    Route::post('/settings/security/update', [SettingsController::class, 'updateSecurity'])->name('settings.security.update');

    // Members
    Route::get('/members/categories', [MemberController::class, 'categories'])->name('members.categories');
    Route::resource('members', MemberController::class);

    // Finance
    Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');
    Route::get('/finance/reports', [FinancialReportController::class, 'index'])->name('finance.reports');
    Route::get('/finance/settings', [FinanceController::class, 'settings'])->name('finance.settings');
    Route::get('/finance/receipt/{contribution}', [FinanceController::class, 'receipt'])->name('finance.receipt');
    Route::resource('finance', FinanceController::class);
    Route::resource('expenses', ExpenseController::class);
    Route::resource('reconciliation', ReconciliationController::class);

    // Groups
    Route::get('/groups/communities', [GroupController::class, 'communities'])->name('groups.communities');
    Route::get('/groups/activities', [GroupController::class, 'activities'])->name('groups.activities');
    Route::resource('groups', GroupController::class);

    // Communications
    Route::get('/communications/announcements', [CommunicationController::class, 'announcements'])->name('communications.announcements');
    Route::resource('communications', CommunicationController::class);
    Route::post('/message-templates/test', [MessageTemplateController::class, 'test'])->name('message-templates.test');
    Route::resource('message-templates', MessageTemplateController::class);
    Route::post('/api-configs/{api_config}/test', [ApiConfigController::class, 'testConnection'])->name('api-configs.test');
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
    });
});
    // Elections
    Route::get('/elections/results', [ElectionController::class, 'results'])->name('elections.results');
    Route::get('/elections/{election}/vote', [ElectionController::class, 'vote'])->name('elections.vote');
    Route::resource('elections', ElectionController::class);
});
