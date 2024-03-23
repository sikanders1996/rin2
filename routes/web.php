<?php

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});


// User List Page
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/user/impersonate/{id}', [UserController::class, 'impersonateUser'])->name('user.impersonate');
Route::put('/user/{notification}/read', [UserController::class, 'markAsRead'])->name('notifications.markAsRead');

Route::get('/user/edit/{id}', [UserController::class, 'editUser'])->name('user.edit');
Route::put('/user/update/{id}', [UserController::class, 'updateUser'])->name('user.update');

Route::post('/verify-phone', [UserController::class, 'verifyPhone'])->name('verify.phone');
Route::post('/verify-phone-code', [UserController::class, 'verifyPhoneCode'])->name('verify.phone-code');

Route::get('/notification/create',[NotificationController::class, 'create'])->name('notification.create');
Route::post('/notification/store',[NotificationController::class, 'store'])->name('notifications.store');
