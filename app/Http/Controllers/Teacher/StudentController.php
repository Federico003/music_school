<?php

namespace App\Http\Controllers\Teacher;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CourseEnrollment;
use App\Models\Lesson;


class StudentController extends TeacherController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('teacher.student.index');
    }

    public function list(): JsonResponse
    {
        // Recupera solo gli utenti che hanno il ruolo "studente"
        /*$students = User::select('users.id', 'users.name', 'users.email', 'courses.name as course_name', 'users.created_at', 'users.updated_at')
            ->join('course_enrollments', 'users.id', '=', 'course_enrollments.user_id')
            ->join('courses', 'course_enrollments.course_id', '=', 'courses.id')
            ->where('course_enrollments.teacher_id', auth()->id())  // Solo corsi con il tuo ID insegnante
            ->get();*/

            $students = User::select('users.id', 'users.name', 'users.email', 'users.created_at', 'users.updated_at', DB::raw('GROUP_CONCAT(courses.name SEPARATOR ", ") as course_name'))
                ->join('course_enrollments', 'users.id', '=', 'course_enrollments.user_id')
                ->join('courses', 'course_enrollments.course_id', '=', 'courses.id')
                ->where('course_enrollments.teacher_id', auth()->id())
                ->groupBy('users.id', 'users.name', 'users.email', 'users.created_at', 'users.updated_at')
                ->get();

        



        return DataTables::of($students)
            ->addColumn('actions', function ($students) {
                $buttons = '<span class="mr-1"><a href="student/'.$students->id.'" data-id="'.$students->id.'" class="btn btn-default waves-effect" title="Visualizza"><i class="material-icons">remove_red_eye</i></a></span>';
                $buttons .= '<span class="mr-1"><a href="student/'.$students->id.'/print" data-id="'.$students->id.'" class="btn btn-default waves-effect" title="Stampa"><i class="material-icons">print</i></a></span>';

                return $buttons;
            })
            ->editColumn('rolename', function ($students) {
                return $students->getRoleName();
            })
            ->rawColumns(['actions'])
            ->make(true);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validazione dei dati
        $request->validate([
            'course_enrollment_id' => 'required|exists:course_enrollments,id',
            'day' => 'required|date',
            'time' => 'required',
            'duration' => 'required|integer|min:1'
        ]);

        // Creazione della lezione
        Lesson::create($request->all());

        return redirect()->back()->with('success', 'Lezione aggiunta con successo!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
{
    $user = User::findOrFail($id);

    // Recupera i corsi a cui l'utente è iscritto
    $finalCourses = CourseEnrollment::where('user_id', $user->id)
        ->with('course') 
        ->get()
        ->map(fn($enrollment) => $enrollment->course);

    $role = $user->roles->first(); 

    $initialImage = 'images/initials/' . $user->initial . '.png';
    if (!file_exists(public_path($initialImage))) {
        $initialImage = 'images/initials/default.png';
    }

    return view('teacher.student.show', compact('user', 'finalCourses', 'role', 'initialImage'));
}

/*public function showLessons($studentId, Request $request)
{
    // Recupera il corso selezionato
    $selectedCourseId = $request->input('course_select');

    // Recupera lo studente (user) tramite il suo ID
    $student = User::find($studentId);

    // Inizializza un array per le lezioni
    $lessons = [];

    // Se è stato selezionato un corso
    if ($selectedCourseId) {
        // Recupera l'iscrizione del corso per questo studente e insegnante
        $courseEnrollments = CourseEnrollment::where('user_id', $studentId)
                                              ->where('course_id', $selectedCourseId)
                                              ->where('teacher_id', auth()->user()->id)
                                              ->get();

        // Recupera tutte le lezioni per queste iscrizioni
        $lessons = Lesson::whereIn('course_enrollment_id', $courseEnrollments->pluck('id'))->get();
    }

    // Risposta JSON con le lezioni trovate
    return response()->json([
        'lessons' => $lessons->map(function ($lesson) {
            return [
                'day' => $lesson->day,
                'time' => $lesson->time,
                'duration' => $lesson->duration
            ];
        })
    ]);
}*/

public function showLessons($studentId, Request $request)
{
    $selectedCourseId = $request->input('course_select');
    $student = User::find($studentId);
    
    if ($selectedCourseId) {
        $courseEnrollments = CourseEnrollment::where('user_id', $studentId)
                                              ->where('course_id', $selectedCourseId)
                                              ->where('teacher_id', auth()->user()->id)
                                              ->get();

        $lessons = Lesson::whereIn('course_enrollment_id', $courseEnrollments->pluck('id'))->get();
    } else {
        $lessons = collect();
    }

    // Invece di restituire JSON, ritorniamo un pezzo di HTML
    return view('partials.lessons', compact('lessons'));
}

public function getLessons(Request $request, $userId)
{
    $courseId = $request->input('course_select');
    $studentId = $request->input('student_id');
    
    // Logica per recuperare le lezioni per l'utente e il corso
    $lessons = Lesson::where('course_id', $courseId)->where('student_id', $studentId)->get();

    // Restituisci la vista con le lezioni o un altro formato di risposta
    return view('lesson.partials.lessons', compact('lessons'));
}







    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $teacher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Teacher $teacher)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        //
    }
}
