<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

/**
 * Per disabilitare la registrazione: 
 * 'register' => false
 */
Auth::routes(['register' => true, 'confirm' => false, 'reset' => true, 'verify' => false]);

Route::get('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:admin', 'status'])->group(function () {
    Route::get('/', function () {
        return redirect('/admin/home');
    });
});

Route::middleware(['auth', 'role:admin', 'status'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/home', [App\Http\Controllers\Admin\HomeController::class, 'index'])->name('home');

    Route::resource('user', App\Http\Controllers\Admin\UserController::class)->except(['show']);
    Route::prefix('user')->scopeBindings()->name('user.')->group(function () {
        Route::post('/list', [App\Http\Controllers\Admin\UserController::class, 'list'])->name('list');
        Route::get('/exportToExcel', [App\Http\Controllers\Admin\UserController::class, 'exportToExcel'])->name('exportToExcel');
        Route::get('/import', [App\Http\Controllers\Admin\UserController::class, 'showImport'])->name('showImport');
        Route::post('/import', [App\Http\Controllers\Admin\UserController::class, 'import'])->name('import');
    });

    Route::get('/users/{user}/print', [App\Http\Controllers\Admin\UserController::class, 'print'])->name('users.print'); //bottone stampa nella DataTable
});

Route::get('/users/{user}/print', [App\Http\Controllers\Admin\UserController::class, 'print'])->name('users.print'); //bottone stampa nel modifica utente

Route::middleware(['auth', 'role:user', 'status'])->prefix('user')->name('user.')->group(function () {
    Route::get('/home', [App\Http\Controllers\Users\HomeController::class, 'index'])->name('home');
});
