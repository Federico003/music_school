<?php

namespace App\Http\Controllers\Teacher;

use App\Models\Lesson;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CourseEnrollment;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use App\Services\LessonService;
use Illuminate\Http\JsonResponse;
class LessonController extends Controller
{
    protected LessonService $lessonService;

    public function __construct(LessonService $lessonService)
    {
        $this->lessonService = $lessonService;
    }

    public function index(Request $request): View
    {
        if ($request->ajax()) {
            $lessons = Lesson::select(['id', 'day', 'time', 'duration']);
            return DataTables::of($lessons)
                ->addColumn('action', function ($lesson) {
                    return '
                        <button class="btn btn-primary btn-sm" onclick="editLesson('.$lesson->id.')">Modifica</button>
                        <button class="btn btn-danger btn-delete" data-id="'.$lesson->id.'">Elimina</button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('teacher.lesson.index');
    }

    public function store(Request $request, $userId)
{
    // Verifica i dati che arrivano
    //dd($request->all());

    // Prendi i dati dal form
    $studentId = $userId;
    $courseId = $request->course_id;
    $day = $request->day;
    $time = $request->time;
    $duration = $request->duration;

    //dd($studentId, $courseId, $day, $time, $duration);

    // Cerca la riga giusta nella tabella course_enrollments
    $courseEnrollment = CourseEnrollment::where('teacher_id', auth()->user()->id)  // Docente loggato
        ->where('user_id', $studentId)  // ID dello studente
        ->where('course_id', $courseId)  // ID del corso
        ->first();  // Ottieni il primo record che corrisponde ai criteri

    //dd($courseEnrollment);  // Verifica che venga trovato il course_enrollment

    // Se non viene trovato, restituisci errore
    if (!$courseEnrollment) {
        return back()->withErrors(['error' => 'Course enrollment not found']);
    }

    // Salva la lezione
    $lesson = new Lesson([
        'course_enrollment_id' => $courseEnrollment->id,  // Usa l'ID corretto di course_enrollments
        'day' => $day,
        'time' => $time,
        'duration' => $duration,
    ]);

    $lesson->save();

    return back()->with('success', 'Lesson created successfully');
}

    public function create()
{
    $courseEnrollments = CourseEnrollment::with(['course', 'student'])->get();
    return view('teacher.student.show', compact('courseEnrollments'));
}

public function edit($lessonId)
{
    // Recupera la lezione in base all'ID
    $lesson = Lesson::findOrFail($lessonId);
    
    // Restituisci la vista con il modulo di modifica
    return view('lesson.edit', compact('lesson'));
}


public function update(Request $request, $lessonId)
{
    // Recupera la lezione da aggiornare
    $lesson = Lesson::findOrFail($lessonId);

    // Valida i dati della richiesta
    $validated = $request->validate([
        'day' => 'required|date',  // Modifica queste regole di validazione a seconda delle tue necessità
        'time' => 'required',
        'duration' => 'required|integer',
    ]);

    // Aggiorna i dati della lezione
    $lesson->update($validated);

    // Redirige al percorso con le lezioni aggiornate
    return redirect()->route('teacher.student.lessons', ['userId' => $lesson->student_id]);
}

public function updateLesson(Request $request)
{
    // Validazione dei dati
    $validated = $request->validate([
        'lessonId' => 'required|integer', // Aggiungi la validazione per lessonId
        'lessonDate' => 'required|date',
        'lessonTime' => 'required|date_format:H:i', // Usa date_format per validare l'orario
        'lessonDuration' => 'required|integer',
        'course_id' => 'required|integer', // Aggiungi la validazione per course_id
        'student_id' => 'required|integer', // Aggiungi la validazione per student_id
    ]);

    // Trova la lezione da aggiornare
    $lesson = Lesson::find($request->lessonId);
    if (!$lesson) {
        return response()->json([
            'error' => 'Lezione non trovata',
        ], 404); // Restituisci un errore 404 se la lezione non esiste
    }

    // Aggiorna la lezione nel database
    $lesson->day = $request->lessonDate;
    $lesson->time = $request->lessonTime;
    $lesson->duration = $request->lessonDuration;
    $lesson->save();

    // Trova il course_enrollment_id in base a course_id e student_id
    $courseEnrollment = CourseEnrollment::where('teacher_id', auth()->user()->id)  // Docente loggato
        ->where('user_id', $request->student_id)  // ID dello studente
        ->where('course_id', $request->course_id)  // ID del corso
        ->first();

    if (!$courseEnrollment) {
        return response()->json([
            'error' => 'Course enrollment non trovato',
        ], 404); // Restituisci un errore 404 se il course enrollment non esiste
    }

    // Ottieni le lezioni relative al course_enrollment_id
    $lessons = Lesson::where('course_enrollment_id', $courseEnrollment->id)->get();

    // Restituisci una risposta JSON di successo
    return response()->json([
        'message' => 'Lezione aggiornata con successo',
        'updatedLessonsHtml' => view('partials.lessons', ['lessons' => $lessons])->render(), // Passa solo le lezioni del corso selezionato
    ]);
}


public function deleteLesson(Request $request)
{
    // Validazione dei dati
    $validated = $request->validate([
        'lesson_id' => 'required|integer', // ID della lezione
        'course_id' => 'required|integer', // ID del corso
        'student_id' => 'required|integer', // ID dello studente
    ]);

    // Trova la lezione da eliminare
    $lesson = Lesson::find($request->lesson_id);
    if (!$lesson) {
        return response()->json([
            'error' => 'Lezione non trovata',
        ], 404); // Restituisci un errore 404 se la lezione non esiste
    }

    // Verifica che la lezione appartenga al corso e allo studente corretti
    $courseEnrollment = CourseEnrollment::where('teacher_id', auth()->user()->id)  // Docente loggato
        ->where('user_id', $request->student_id)  // ID dello studente
        ->where('course_id', $request->course_id)  // ID del corso
        ->first();

    if (!$courseEnrollment || $lesson->course_enrollment_id != $courseEnrollment->id) {
        return response()->json([
            'error' => 'Lezione non valida o non autorizzata',
        ], 403); // Restituisci un errore 403 se la lezione non è valida
    }

    // Elimina la lezione
    $lesson->delete();

    // Ottieni le lezioni relative al course_enrollment_id
    $lessons = Lesson::where('course_enrollment_id', $courseEnrollment->id)->get();

    // Restituisci una risposta JSON di successo
    return response()->json([
        'success' => true,
        'message' => 'Lezione eliminata con successo',
        'updatedLessonsHtml' => view('partials.lessons', ['lessons' => $lessons])->render(), // Passa solo le lezioni del corso selezionato
    ]);
}



public function destroy(Lesson $lesson): JsonResponse
{
    // Recupera e elimina la lezione
    /*$lesson = Lesson::findOrFail($lessonId);
    $studentId = $lesson->student_id; // Salva l'ID dello studente prima di eliminare la lezione
    $lesson->delete();

    // Restituisci una risposta JSON di successo
    return response()->json([
        'message' => 'Lezione eliminata con successo',
        'studentId' => $studentId, // Invia l'ID dello studente per aggiornare la lista delle lezioni
    ]);*/
    return $this->lessonService->deleteLesson($lesson);
}

public function getLessonDetails($lessonId)
{
    // Recupera i dettagli della lezione
    $lesson = Lesson::find($lessonId);
    
    // Se la lezione non esiste, puoi restituire un errore
    if (!$lesson) {
        return response()->json(['error' => 'Lezione non trovata'], 404);
    }

    return response()->json([
        'lesson' => $lesson,
    ]);
}

public function getLessonsData(Request $request)
{
    $lessons = Lesson::query();

    return datatables()->of($lessons)
        ->addColumn('action', function ($lesson) {
            return '
                <button class="btn btn-primary btn-sm" onclick="editLesson('.$lesson->id.')">Modifica</button>
                <button class="btn btn-danger btn-delete waves-effect" data-id="'.$lesson->id.'">Elimina</button>
            ';
        })
        ->rawColumns(['action'])
        ->toJson();
}


}