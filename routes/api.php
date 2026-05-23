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
    Route::get('/profile/saved-methods', [MemberApiController::class, 'savedMethods']);
    
    // Contributions
    Route::get('/contributions', [MemberApiController::class, 'contributions']);
    Route::get('/contributions/{id}', [MemberApiController::class, 'getReceipt']);
    Route::get('/contributions/{id}/receipt', [MemberApiController::class, 'getReceipt']);
    Route::get('/contribution-types', [MemberApiController::class, 'contributionTypes']);
    Route::post('/contributions/pay', [MemberApiController::class, 'initiatePayment']);
    
    // Events & Attendance
    Route::get('/events', [MemberApiController::class, 'events']);
    Route::post('/events/attendance', [MemberApiController::class, 'markAttendance']);
    
    // Groups/Communities
    Route::get('/groups', [MemberApiController::class, 'groups']);
    Route::post('/groups/{id}/join', [MemberApiController::class, 'joinGroup']);
    Route::post('/groups/{id}/leave', [MemberApiController::class, 'leaveGroup']);
    Route::get('/groups/{id}/stats', [MemberApiController::class, 'groupStats']);
    Route::get('/groups/{id}/members', [MemberApiController::class, 'groupMembers']);
    Route::get('/groups/{id}/meetings', [MemberApiController::class, 'groupMeetings']);
    Route::post('/groups/{id}/messages', [MemberApiController::class, 'sendGroupMessage']);
    
    // Elections & Voting
    Route::get('/elections', [MemberApiController::class, 'elections']);
    Route::post('/elections/vote', [MemberApiController::class, 'castVote']);
    
    // Announcements & Notifications
    Route::get('/announcements', [MemberApiController::class, 'announcements']);
    
    // Security
    Route::post('/change-password', [MemberApiController::class, 'changePassword']);
    
    // Logout
    Route::post('/logout', [MemberApiController::class, 'logout']);
});
