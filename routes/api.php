<?php

use App\Http\Controllers\Api\MemberApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/login', [MemberApiController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Dashboard & Profile
    Route::get('/dashboard', [MemberApiController::class, 'dashboard']);
    Route::get('/profile', [MemberApiController::class, 'profile']);
    Route::post('/profile/update', [MemberApiController::class, 'updateProfile']);
    Route::get('/id-card', [MemberApiController::class, 'idCard']);
    
    // Contributions
    Route::get('/contributions', [MemberApiController::class, 'contributions']);
    Route::get('/contribution-types', [MemberApiController::class, 'contributionTypes']);
    Route::post('/contributions/pay', [MemberApiController::class, 'initiatePayment']);
    
    // Events & Attendance
    Route::get('/events', [MemberApiController::class, 'events']);
    Route::post('/events/attendance', [MemberApiController::class, 'markAttendance']);
    
    // Groups/Communities
    Route::get('/groups', [MemberApiController::class, 'groups']);
    
    // Elections & Voting
    Route::get('/elections', [MemberApiController::class, 'elections']);
    Route::post('/elections/vote', [MemberApiController::class, 'castVote']);
    
    // Announcements
    Route::get('/announcements', [MemberApiController::class, 'announcements']);
    
    // Logout
    Route::post('/logout', [MemberApiController::class, 'logout']);
});
