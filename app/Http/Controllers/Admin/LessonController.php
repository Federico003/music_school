<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\LessonService;
use App\Exports\UsersExport;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Imports\UserImport;
use App\Models\User;
use App\Models\Course;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Log;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Models\Lesson;
use Illuminate\Support\Carbon;

class LessonController extends AdminController
{
    protected $lessonService;

    public function __construct(LessonService $lessonService)
    {
        $this->lessonService = $lessonService;
    }

    public function show(): View
    {
         $lessons = Lesson::all();

         $events = $lessons->map(function ($lesson) {
            $start = Carbon::parse($lesson->day . ' ' . $lesson->time);
            $end = (clone $start)->addMinutes($lesson->duration);

            return [
                'id' => $lesson->id,
                'title' => 'Lezione', // Puoi personalizzarlo con il nome del corso, ecc.
                'start' => $start->toIso8601String(),
                'end' => $end->toIso8601String(),
            ];
        });

        //dd($events);

        return view('admin.lesson.index');
    }

    public function index(): View
    {
        return view('admin.lesson.index');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'course_enrollment_id' => 'required|exists:course_enrollments,id',
            'day' => 'required|date',
            'time' => 'required',
            'duration' => 'required|integer|min:15' // Durata minima 1 minuto
        ]);

        $lesson = $this->lessonService->createLesson($data);

        return redirect()->back()->with('success', 'Lezione creata con successo!');
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'day' => 'required|date',
            'time' => 'required',
            'duration' => 'required|integer|min:1'
        ]);

        $lesson = $this->lessonService->updateLesson($id, $data);

        return redirect()->back()->with('success', 'Lezione aggiornata con successo!');
    }


    public function destroy($id)
    {
        $this->lessonService->deleteLesson($id);
        return redirect()->back()->with('success', 'Lezione eliminata con successo!');
    }


    public function getEvents()
    {
        // Recupera tutte le lezioni con le relazioni necessarie
        $lessons = Lesson::with(['courseEnrollment.student', 'courseEnrollment.course', 'courseEnrollment.teacher'])->get();

        // Mappa le lezioni nel formato richiesto da FullCalendar
        $events = $lessons->map(function ($lesson) {
            return [
                'title' => $lesson->courseEnrollment->student->name . ' - ' . $lesson->courseEnrollment->course->name,  // Nome studente e nome corso
                'start' => $lesson->day . 'T' . $lesson->time,  // Ora di inizio
                'end' => $lesson->day . 'T' . date('H:i', strtotime("+{$lesson->duration} minutes", strtotime($lesson->time))), // Ora di fine
            ];
        });
        dd($events);
        return response()->json($events);
    }


    public function events(Request $request)
    {

        $lessons = Lesson::all();

        $events = $lessons->map(function ($lesson) {
            $start = Carbon::parse($lesson->day . ' ' . $lesson->time);
            $end = (clone $start)->addMinutes($lesson->duration);

            return [
                'id' => $lesson->id,
                'title' => 'Lezione', // Puoi personalizzarlo con il nome del corso, ecc.
                'start' => $start->toIso8601String(),
                'end' => $end->toIso8601String(),
            ];
        });

        //dd($events);
        return response()->json($events);
    }
}
