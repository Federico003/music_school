<?php

namespace App\Services;

use App\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class LessonService
{
    public function createLesson($data){
        return Lesson::create($data);
    }

    public function updateLesson($id, $data){
        $lesson = Lesson::findOrFail($id);
        $lesson->update($data);
        return $lesson;
    }


    public function getLessonsByEnrollment($courseEnrollmentId)
    {
        return Lesson::where('course_enrollment_id', $courseEnrollmentId)->get();
    }


    public function deleteLesson(Lesson $lesson): JsonResponse
    {
        $lesson->delete();

        return Response::json([
            'success' => true,
            'message' => 'Corso eliminato con successo',
        ], 200);
    }
}
