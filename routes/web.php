<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\Auth\Login;
use App\Http\Controllers\Auth\Logout;
use App\Http\Controllers\Auth\Register;
use App\Http\Controllers\CallController;
use App\Http\Controllers\ChipController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [ChipController::class, 'index']);

// Serve storage files (for environments where symlink doesn't work)
Route::get('/storage/{path}', function ($path) {
    $file = storage_path('app/public/' . $path);
    if (!file_exists($file)) {
        abort(404);
    }
    return response()->file($file);
})->where('path', '.*');

// Protected routes
Route::middleware('auth')->group(function () {

    // Chips
    Route::post('/chips', [ChipController::class, 'store']);
    Route::get('/chips/{chip}/edit', [ChipController::class, 'edit']);
    Route::put('/chips/{chip}', [ChipController::class, 'update']);
    Route::delete('/chips/{chip}', [ChipController::class, 'destroy']);
    Route::post('/chips/{chip}/reply', [ChipController::class, 'reply']);

    // Tasks
    Route::resource('tasks', TaskController::class)
        ->only(['index', 'store', 'update', 'destroy']);

    // Meetings
    Route::post('/meetings', [MeetingController::class, 'store'])->name('meetings.store');

    // Notifications
    Route::post('/notifications/{notification}/read', function ($notificationId) {
        $user = Auth::user();
        $notification = $user?->notifications()->find($notificationId);
        $notification?->markAsRead();
        return back();
    })->name('notifications.read');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Conversations & Messaging
    Route::get('/conversations', [ConversationController::class, 'index'])->name('conversations.index');
    Route::get('/conversations/{conversation}', [ConversationController::class, 'show'])->name('conversations.show');
    Route::post('/conversations', [ConversationController::class, 'store'])->name('conversations.store');
    Route::post('/conversations/private/{user}', [ConversationController::class, 'createPrivate'])->name('conversations.private');
    Route::post('/conversations/{conversation}/participants', [ConversationController::class, 'addParticipants'])->name('conversations.participants');
    
    // Messages
    Route::post('/conversations/{conversation}/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
    
    // Calls
    Route::post('/conversations/{conversation}/calls', [CallController::class, 'initiate'])->name('calls.initiate');
    Route::post('/calls/{call}/answer', [CallController::class, 'answer'])->name('calls.answer');
    Route::post('/calls/{call}/end', [CallController::class, 'end'])->name('calls.end');
    Route::post('/calls/{call}/screen-share', [CallController::class, 'toggleScreenShare'])->name('calls.screen-share');
});

// Admin approval routes
Route::middleware(['auth', 'can:isAdmin'])->group(function () {
    Route::post('/admin/approve/{user}', [AdminUserController::class, 'approve'])->name('admin.approve');
    Route::patch('/admin/reject/{user}', [AdminUserController::class, 'reject'])->name('admin.reject');
});


// Auth routes
Route::view('/register', 'auth.register')->middleware('guest')->name('register');
Route::post('/register', Register::class)->middleware('guest');
Route::view('/login', 'auth.login')->middleware('guest')->name('login');
Route::post('/login', Login::class)->middleware('guest');
Route::post('/logout', Logout::class)->middleware('auth')->name('logout');

Route::post('/notifications/clear', function () {
    $user = Auth::user();
    $user->unreadNotifications->markAsRead();
    return back();
})->middleware('auth')->name('notifications.clear');
