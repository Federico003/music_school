<?php

namespace App\Models;

use App\Models\TeacherView; // Il Model della View
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Model;

class TeacherCourse extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($teacherCourse) {
            // Controlla se il teacher_id esiste nella view
            $exists = TeacherView::where('model_id', $teacherCourse->teacher_id)->exists();

            if (!$exists) {
                throw ValidationException::withMessages([
                    'teacher_id' => 'Il docente selezionato non ha il ruolo di "teacher".'
                ]);
            }
        });
    }
}
