<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\LessonController as AdminLessonController;
//-----------------------------------------------
use App\Http\Controllers\Teacher\StudentController;
use App\Http\Controllers\Teacher\LessonController as TeacherLessonController;

use App\Models\Lesson;

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

//Route::get('/admin/students', [App\Http\Controllers\Admin\UserController::class, 'students'])->name('admin.students');
//Route::get('/admin/teachers', [App\Http\Controllers\Admin\UserController::class, 'teachers'])->name('admin.teachers');


Route::middleware(['auth', 'role:admin', 'status'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/home', [App\Http\Controllers\Admin\HomeController::class, 'index'])->name('home');

    Route::get('/students', [App\Http\Controllers\Admin\UserController::class, 'students'])->name('students');
    Route::get('/teachers', [App\Http\Controllers\Admin\UserController::class, 'teachers'])->name('teachers');
    
    // Gestione utenti 
    Route::resource('user', App\Http\Controllers\Admin\UserController::class)->except(['show']);
    Route::resource('course', App\Http\Controllers\Admin\CourseController::class)->except(['show']);
    Route::resource('lesson', App\Http\Controllers\Admin\LessonController::class);
    
    //Route::get('/user/students', [App\Http\Controllers\Admin\UserController::class, 'students'])->name('user.students');
    //Route::get('/user/teachers', [App\Http\Controllers\Admin\UserController::class, 'teachers'])->name('user.teachers');

    Route::prefix('user')->scopeBindings()->name('user.')->group(function () {
        // Rotte personalizzate gestione utenti 
        /*Route::post('/list', [App\Http\Controllers\Admin\UserController::class, 'list'])->name('list');
        Route::get('/{user}/print', [App\Http\Controllers\Admin\UserController::class, 'print'])->name('print');
        Route::get('/exportToExcel', [App\Http\Controllers\Admin\UserController::class, 'exportToExcel'])->name('exportToExcel');
        Route::get('/import', [App\Http\Controllers\Admin\UserController::class, 'showImport'])->name('showImport');
        Route::post('/import', [App\Http\Controllers\Admin\UserController::class, 'import'])->name('import');*/

        Route::get('/students', [App\Http\Controllers\Admin\UserController::class, 'students'])->name('students');
        Route::get('/teachers', [App\Http\Controllers\Admin\UserController::class, 'teachers'])->name('teachers');


        Route::post('/list', [App\Http\Controllers\Admin\UserController::class, 'list'])->name('list');
        Route::get('/{user}/print', [App\Http\Controllers\Admin\UserController::class, 'print'])->name('print');
        Route::get('/exportToExcel', [App\Http\Controllers\Admin\UserController::class, 'exportToExcel'])->name('exportToExcel');
        Route::get('/import', [App\Http\Controllers\Admin\UserController::class, 'showImport'])->name('showImport');
        Route::post('/import', [App\Http\Controllers\Admin\UserController::class, 'import'])->name('import');
        Route::get('/{user}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('edit');
        Route::patch('/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('update');
        Route::post('/{user}/update-course-enrollments', [UserController::class, 'updateCourseEnrollments'])->name('updateCourseEnrollments');

        //Route::patch('/{user}/debug', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('debug');


        //Route::get('/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('admin.user.create');
    });

    Route::prefix('course')->scopeBindings()->name('course.')->group(function () {
        Route::post('/list', [App\Http\Controllers\Admin\CourseController::class, 'list'])->name('list');
        Route::get('/{user}/print', [App\Http\Controllers\Admin\CourseController::class, 'print'])->name('print');
        Route::get('/exportToExcel', [App\Http\Controllers\Admin\CourseController::class, 'exportToExcel'])->name('exportToExcel');
        Route::get('/import', [App\Http\Controllers\Admin\CourseController::class, 'showImport'])->name('showImport');
        Route::post('/import', [App\Http\Controllers\Admin\CourseController::class, 'import'])->name('import');
    });

    Route::prefix('lesson')->name('lesson.')->group(function () {
        Route::post('/store', [App\Http\Controllers\Admin\LessonController::class, 'store'])->name('store');
        Route::post('/{id}/update', [App\Http\Controllers\Admin\LessonController::class, 'update'])->name('update');
        Route::post('/{id}/delete', [App\Http\Controllers\Admin\LessonController::class, 'destroy'])->name('destroy');
    });
    
});

Route::middleware(['auth', 'role:teacher', 'status'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/home', [App\Http\Controllers\Teacher\HomeController::class, 'index'])->name('home');

    Route::resource('student', App\Http\Controllers\Teacher\StudentController::class);
    Route::resource('lesson', App\Http\Controllers\Teacher\LessonController::class); // Usa l'alias qui

    Route::prefix('student')->scopeBindings()->name('student.')->group(function () {
        Route::post('/list', [App\Http\Controllers\Teacher\StudentController::class, 'list'])->name('list');
        Route::post('/lessons/{id}', [App\Http\Controllers\Teacher\StudentController::class, 'showLessons'])->name('lessons');
        Route::post('/{id}', [App\Http\Controllers\Teacher\LessonController::class, 'store'])->name('lessonsStore');
    });

    Route::prefix('lesson')->scopeBindings()->name('lesson.')->group(function() {
        // Aggiungi la rotta per ottenere i dettagli della lezione
        Route::get('/get-lesson-details/{lessonId}', [App\Http\Controllers\Teacher\LessonController::class, 'getLessonDetails'])->name('getLessonDetails');
        
        // Aggiungi la rotta per aggiornare i dettagli della lezione
        Route::post('/update-lesson', [App\Http\Controllers\Teacher\LessonController::class, 'updateLesson'])->name('update-lesson');
        Route::post('/delete-lesson', [App\Http\Controllers\Teacher\LessonController::class, 'deleteLesson'])->name('teacher.lesson.delete');
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

// Conteggio studenti totali
Route::prefix('api')->get('/student-count', [UserController::class, 'getStudentCount']);

// Conteggio studenti attivi
Route::prefix('api')->get('/active-student-count', [UserController::class, 'getActiveStudentCount']);


Route::post('/admin/users/{user}/store-courses', [UserController::class, 'storeCourses'])->name('admin.users.storeCourses');


Route::get('/get-courses', [CourseController::class, 'getCourses'])->name('admin.courses.list');

Route::post('/admin/users/{id}/update-course-enrollments', [UserController::class, 'updateCourseEnrollments'])->name('admin.users.updateCourseEnrollments');

//Recupera le lezione dal database
// Definisci la route per ottenere gli eventi delle lezioni
//Route::get('/lessons/events', [LessonController::class, 'getEvents']);
