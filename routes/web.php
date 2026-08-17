<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\AccessController;
use App\Http\Controllers\SubscriptionController;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::post('/login', [AuthController::class, 'login'])->name('login.store');

Route::middleware(['auth', 'verified', 'subscription'])->group(function () {

    Route::view('dashboard', 'dashboard')->name('dashboard');

    /** -------------------- Subscription -------------------- **/
    Route::prefix('subscription')->group(function () {
        Route::get('/', [SubscriptionController::class, 'index'])->name('subscription');
        Route::post('/store', [SubscriptionController::class, 'store'])->name('subscription.store');
    });

    /** -------------------- Users -------------------- **/
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('user');
        Route::post('/store', [UserController::class, 'store'])->name('user.store');
    });

    /** -------------------- Business -------------------- **/
    Route::prefix('business')->group(function () {
        Route::get('/', [BusinessController::class, 'index'])->name('business');
        Route::post('/store', [BusinessController::class, 'store'])->name('business.store');
    });

    /** -------------------- Access -------------------- **/
    Route::prefix('access')->group(function () {
        Route::get('/', [AccessController::class, 'index'])->name('access');

        Route::post('/permisson/store', [AccessController::class, 'permissionStore'])->name('permission.store');
        Route::post('/permission/update', [AccessController::class, 'permissionUpdate'])->name('permission.update');
        Route::delete('/permission/{permission}/destroy', [AccessController::class, 'permissionDestroy'])->name('permission.destroy');

        Route::post('/role/store', [AccessController::class, 'roleStore'])->name('role.store');
        Route::delete('/role/{role}/destroy', [AccessController::class, 'roleDestroy'])->name('role.destroy');
    });


});

require __DIR__.'/settings.php';
