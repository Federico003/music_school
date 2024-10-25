<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
})->name('login');

Auth::routes(['register'=>true,'confirm' => false, 'reset' => true, 'verify' => false]);

Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'create'])->name('create');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'store'])->name('store');

Route::get('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:admin', 'status'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/home', [App\Http\Controllers\Admin\HomeController::class, 'index'])->name('home');

    Route::resource('user', App\Http\Controllers\Admin\UserController::class)->except(['show']);
    Route::prefix('user')->scopeBindings()->name('user.')->group(function () {
        Route::post('/list', [App\Http\Controllers\Admin\UserController::class, 'list'])->name('list');
        Route::get('/exportToExcel', [App\Http\Controllers\Admin\UserController::class, 'exportToExcel'])->name('exportToExcel');
    });

    Route::get('/users/{user}/print', [App\Http\Controllers\Admin\UserController::class, 'print'])->name('users.print'); //bottone stampa nella DataTable
});

Route::get('/users/{user}/print', [App\Http\Controllers\Admin\UserController::class, 'print'])->name('users.print'); //bottone stampa nel modifica utente

Route::middleware(['auth', 'role:customer', 'status'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/home', [App\Http\Controllers\Customer\HomeController::class, 'index'])->name('home');
});

Route::middleware(['guest'])->prefix('auth')->name('password.')->group(function () {
    Route::get('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'forgotForm'])->name('forgot');
    Route::post('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('resetEmail');
    Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'resetForm'])->name('reset');
    Route::post('/reset-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'reset'])->name('update');
});

