<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = ['course_enrollment_id', 'day', 'time', 'duration'];

    // Relazione con CourseEnrollment
    public function courseEnrollment()
    {
        return $this->belongsTo(CourseEnrollment::class);
    }

    // Relazione con Student attraverso CourseEnrollment
    public function student()
{
    return $this->belongsTo(Student::class, 'student_id');  // supponendo che hai student_id in lesson
}

    // Relazione con Teacher attraverso CourseEnrollment
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id'); // Se hai un campo teacher_id direttamente in Lesson
    }

    // Relazione con Course attraverso CourseEnrollment
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
