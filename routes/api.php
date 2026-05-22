<?php

use App\Http\Controllers\Api\MemberApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/login', [MemberApiController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Dashboard
    Route::get('/dashboard', [MemberApiController::class, 'dashboard']);

    // Profile
    Route::get('/profile', [MemberApiController::class, 'profile']);
    Route::post('/profile/update', [MemberApiController::class, 'updateProfile']);
    
    // Contributions
    Route::get('/contributions', [MemberApiController::class, 'contributions']);
    Route::get('/contribution-types', [MemberApiController::class, 'contributionTypes']);
    Route::post('/contributions/pay', [MemberApiController::class, 'initiatePayment']);
    
    // Events & Attendance
    Route::get('/events', [MemberApiController::class, 'events']);
    Route::post('/events/attendance', [MemberApiController::class, 'markAttendance']);
    
    // Groups
    Route::get('/groups', [MemberApiController::class, 'groups']);
    
    // Announcements
    Route::get('/announcements', [MemberApiController::class, 'announcements']);
    
    // Logout
    Route::post('/logout', [MemberApiController::class, 'logout']);
});
