<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
})->name('login');

Auth::routes(['register'=>true,'confirm' => false, 'reset' => true, 'verify' => false]);

Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'create'])->name('create');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'store'])->name('store');

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
        Route::get('/importExcel', [App\Http\Controllers\Admin\UserController::class, 'importExcel'])->name('importExcel');
        Route::post('/importExcel', [App\Http\Controllers\Admin\UserController::class, 'importExcel_post'])->name('importExcel_post');
    });

    Route::get('/users/{user}/print', [App\Http\Controllers\Admin\UserController::class, 'print'])->name('users.print'); //bottone stampa nella DataTable
});

Route::get('/users/{user}/print', [App\Http\Controllers\Admin\UserController::class, 'print'])->name('users.print'); //bottone stampa nel modifica utente

Route::middleware(['auth', 'role:customer', 'status'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/home', [App\Http\Controllers\Customer\HomeController::class, 'index'])->name('home');
});

