<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::post('/login', [AuthController::class, 'login'])->name('login.store');

Route::middleware(['auth', 'verified', 'subscription'])->group(function () {

    Route::view('dashboard', 'dashboard')->name('dashboard');

    /** -------------------- Users -------------------- **/
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('user');
        Route::POST('/store', [UserController::class, 'store'])->name('user.store');
    });

    Route::view('business', 'business')->name('business');

    Route::view('access', 'access')->name('access');
});

require __DIR__.'/settings.php';
