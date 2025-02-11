<?php

namespace App\Services;

use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Spatie\Permission\Models\Role;

class CourseService
{
    /**
     * Store a new course in the database.
     *
     * @param  array<string, string>  $courseData  An associative array containing course data.
     * @return Course The created Course model.
     */
    public function storeCourse(array $courseData): Course
    {
        return Course::create([
            'name' => $courseData['name'],
            'description' => $courseData['description'],
        ]);
    }

    /**
     * Update a course's information.
     *
     * @param  Course  $course  The course to be updated.
     * @param  array<string, string>  $courseData  An associative array containing the updated course data, including optional password field.
     */
    public function updateCourse(Course $course, array $courseData): void
    {
        $course->update($courseData);
    }

    /**
     * Delete a course.
     *
     * @param  Course  $course  The course to delete.
     */
    public function deleteCourse(Course $course): JsonResponse
    {
        $course->delete();

        return Response::json([
            'success' => true,
            'message' => 'Corso eliminato con successo',
        ], 200);
    }
}
