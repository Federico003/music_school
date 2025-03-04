<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseEnrollment extends Model
{
    use HasFactory;

    // Definisci i campi che sono "mass assignable"
    protected $fillable = ['user_id', 'course_id', 'teacher_id'];

    // Relazione con lo studente (tabella users)
    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relazione con il corso (tabella courses)
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Relazione con l'insegnante (tabella teachers)
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
