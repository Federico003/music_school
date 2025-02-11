<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/', function () {
    return redirect('/welcome');
});

/**
 * Per disabilitare la registrazione:
 * 'register' => false
 */
Auth::routes(['register' => true, 'confirm' => false, 'reset' => true, 'verify' => false]);

// Rotta per il logout
Route::get('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

/**
 * Rotte per l'utente Admin
 */
Route::get('/admin', function () {
    return redirect('/admin/home');
});

//Route::get('/admin/user/{id}', 'UserController@show')->name('admin.user.index');
//Route::get('/admin/user/{id}', [UserController::class, 'show'])->name('admin.user.show');

Route::post('/admin/users/{user}/store-courses', [UserController::class, 'storeCourses'])->name('admin.users.storeCourses');



Route::middleware(['auth', 'role:admin', 'status'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/home', [App\Http\Controllers\Admin\HomeController::class, 'index'])->name('home');
    
    // Gestione utenti 
    Route::resource('user', App\Http\Controllers\Admin\UserController::class);
    Route::resource('course', App\Http\Controllers\Admin\CourseController::class)->except(['show']);

    Route::prefix('user')->scopeBindings()->name('user.')->group(function () {
        // Rotte personalizzate gestione utenti 
        Route::post('/list', [App\Http\Controllers\Admin\UserController::class, 'list'])->name('list');
        Route::get('/{user}/print', [App\Http\Controllers\Admin\UserController::class, 'print'])->name('print');
        Route::get('/exportToExcel', [App\Http\Controllers\Admin\UserController::class, 'exportToExcel'])->name('exportToExcel');
        Route::get('/import', [App\Http\Controllers\Admin\UserController::class, 'showImport'])->name('showImport');
        Route::post('/import', [App\Http\Controllers\Admin\UserController::class, 'import'])->name('import');
        Route::post('/user/{user}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('show');        
        //Route::get('/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('admin.user.create');
    });

    Route::prefix('course')->scopeBindings()->name('course.')->group(function () {
        Route::post('/list', [App\Http\Controllers\Admin\CourseController::class, 'list'])->name('list');
        Route::get('/{user}/print', [App\Http\Controllers\Admin\CourseController::class, 'print'])->name('print');
        Route::get('/exportToExcel', [App\Http\Controllers\Admin\CourseController::class, 'exportToExcel'])->name('exportToExcel');
        Route::get('/import', [App\Http\Controllers\Admin\CourseController::class, 'showImport'])->name('showImport');
        Route::post('/import', [App\Http\Controllers\Admin\CourseController::class, 'import'])->name('import');
    });
});

/**
 * Rotte per l'utente User
 */
Route::get('/user', function () {
    return redirect('/user/home');
});

 Route::middleware(['auth', 'role:user', 'status'])->prefix('user')->name('user.')->group(function () {
    Route::get('/home', [App\Http\Controllers\User\HomeController::class, 'index'])->name('home');
});


//Route::get('/student-count', [UserController::class, 'getStudentCount']);

Route::prefix('api')->get('/student-count', [UserController::class, 'getStudentCount']);
