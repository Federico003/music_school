<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UsersExport;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Imports\UserImport;
use App\Models\User;
use App\Models\Course;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Log;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class UserController extends AdminController
{
    /**
     * The UserService instance for managing user-related operations.
     */
    protected UserService $userService;

    /**
     * UserService constructor.
     *
     * @param  UserService  $userService  The UserService instance injected for user-related operations.
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the users.
     *
     * @return View The view displaying the list of users.
     */
    public function index(): View
    {
        $isMobile = request()->header('User-Agent'); // Esempio di rilevamento mobile
        return view('admin.user.index', compact('isMobile'));
    }

    /**
     * Return the content of the DataTable.
     *
     * @return JsonResponse A JSON response containing DataTable content for users.
     */
    public function list(Request $request): JsonResponse
{
    $query = User::query()->select('users.*')
        ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
        ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id');

    // Se viene passato un ruolo, filtriamo gli utenti
    if ($request->has('role') && !empty($request->role)) {
        $query->where('roles.name', $request->role);
    }

    return DataTables::of($query)
        ->addColumn('actions', function ($user) {
            $buttons = '<span class="mr-1"><a href="user/'.$user->id.'" data-id="'.$user->id.'" class="btn btn-default waves-effect" title="Visualizza"><i class="material-icons">remove_red_eye</i></a></span>';
            $buttons .= '<span class="mr-1"><a href="user/'.$user->id.'/edit" data-id="'.$user->id.'" class="btn btn-primary waves-effect" title="Modifica"><i class="material-icons">edit</i></a></span>';
            $buttons .= '<span class="mr-1"><a href="user/'.$user->id.'/print" data-id="'.$user->id.'" class="btn btn-default waves-effect" title="Stampa"><i class="material-icons">print</i></a></span>';
            if (Auth::user()->id !== $user->id) {
                $buttons .= '<span class="mr-1"><button id="'.$user->id.'" class="btn btn-danger btn-delete waves-effect" title="Elimina"><i class="material-icons">delete</i></button></span>';
            }
            return $buttons;
        })
        ->editColumn('rolename', function ($user) {
            return $user->roles->pluck('name')->implode(', '); // Mostra tutti i ruoli separati da virgola
        })
        ->rawColumns(['actions'])
        ->make(true);
}




    /**
     * Show the form for creating a new user.
     *
     * @return View The view displaying the user creation form.
     */
    public function create(): View
    {
        $roles = User::getRoles();

        return view('admin.user.create', compact('roles'));
    }

    public function createStudent(): View
    {
        $roles = ['student' => 'Studente'];

        //dd($roles);
        return view('admin.user.createStudent', compact('roles'));
    }

    public function createTeacher(): View
    {
        $roles = ['teacher' => 'Insegnante'];
        //dd($roles);
        return view('admin.user.createTeacher', compact('roles'));
    }
    /**
     * Store a newly created user in storage.
     *
     * @param  UserStoreRequest  $request  The validated user store request.
     * @return RedirectResponse A redirect response based on the user's role.
     */
    public function store(UserStoreRequest $request): RedirectResponse
    {
        $validator = $request->validator;

        if (isset($validator) && $validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validatedData = $request->validated();
        $user = $this->userService->storeUser($validatedData);
        $this->userService->assignRoleToUser($user, $validatedData['role']);

        return Redirect::route(route: 'admin.user.index');
    }


    public function storeStudent(UserStoreRequest $request): RedirectResponse
    {
        $validator = $request->validator;

        if (isset($validator) && $validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validatedData = $request->validated();
        $user = $this->userService->storeUser($validatedData);
        $this->userService->assignRoleToUser($user, $validatedData['role']);

        return Redirect::route(route: 'admin.students');
    }


    public function storeTeacher(UserStoreRequest $request): RedirectResponse
    {
        $validator = $request->validator;

        if (isset($validator) && $validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validatedData = $request->validated();
        $user = $this->userService->storeUser($validatedData);
        $this->userService->assignRoleToUser($user, $validatedData['role']);

        return Redirect::route(route: 'admin.teachers');
    }


    /**
     * Export users to an Excel file.
     *
     * @return mixed The Excel download response for exporting users to a file.
     */
    public function exportToExcel()
    {
        return Excel::download(new UsersExport, time().'-Utenti.xlsx');
    }

    /**
     * Show the form for Inport users from an Excel file.
     *
     * @return View The view displaying the user creation form.
     */
    public function showImport()
    {
        return view('admin.user.import');
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  User  $user  The user to edit.
     * @return View The view displaying the user edit form.
     */
    public function edit(User $user): View
{
    //dd('Sono nel metodo edit');
    $roles = User::getRoles();
    $status = User::getStatuses();

    // Recupera i corsi con i relativi insegnanti
    $courses = Course::with('teachers')->get(); 

    // Recupera i corsi già assegnati all'utente
    $assignedCourses = $user->courses->pluck('id')->toArray(); 

    return view('admin.user.show', compact('user', 'roles', 'status', 'courses', 'assignedCourses'));
}




    /**
     * Update the specified user in storage.
     *
     * @param  UserUpdateRequest  $request  The validated user update request.
     * @param  User  $user  The user to update.
     * @return RedirectResponse A redirect response to the user index.
     */
    public function update(UserUpdateRequest $request, User $user): RedirectResponse
    {

        //Log::info("Utente da aggiornare: ", ['user_id' => $user->id]);
        //dd('Sono nel metodo update');
        //dd($request->all());

        $request->validate([
            'status' => 'required|boolean', // Valida che il campo sia un valore booleano
        ]);

        $validator = $request->validator;

        if (isset($validator) && $validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validatedData = $request->validated();

        // Un utente non può modificare il suo ruolo o lo stato
        if ($user->id == Auth::user()->id) {
            $validatedData['role'] = Auth::user()->getRoleName();
            $validatedData['status'] = Auth::user()->status;
        }

        $this->userService->updateUser($user, $validatedData);
        $this->userService->assignRoleToUser($user, $validatedData['role']);

        return Redirect::route('admin.user.edit', compact('user'));
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  User  $user  The user to be removed.
     * @return JsonResponse A JSON response indicating the result of the user removal.
     */
    public function destroy(User $user): JsonResponse
    {
        return $this->userService->deleteUser($user);
    }

    /**
     * Show the form for Inport users from an Excel file.
     *
     * @return View The view displaying the user creation form.
     */
    public function import(Request $request)
    {
        Excel::import(new UserImport, $request->file('file'));

        return view('admin.user.import'); // Passa i dati alla vista
    }

    /**
     * Stampa una scheda informativa sull'utente.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function print(User $user)
    {
        try {
            if (! $user) {
                return redirect()->route('admin.user.index')->withErrors("L'utente non esiste.");
            }

            $pdf = PDF::loadView('admin.user.pdf.show', compact('user'));

            return $pdf->download('User.pdf');
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    public function show($id){

        // Debug: Verifica se il metodo viene eseguito
        //dd('Metodo show() eseguito per l\'utente con ID:', $id);

        $roles = User::getRoles();

        // Trova l'utente nel database
        $user = User::findOrFail($id);
    
        // Recupera tutti i corsi
        $courses = DB::table('courses')->get(); // Ottieni tutti i corsi

        //dd($courses);
    
        // Recupera i corsi assegnati all'utente
        $assignedCourses = DB::table('teachers_courses')
        ->where('teacher_id', $user->id)
        ->pluck('course_id')
        ->map(function ($courseId) {
            return $courseId; // Formato: "course_id"
        })
        ->toArray();

        //dd($assignedCourses);  // Verifica se vengono recuperati i corsi
    
        // Recupera anche le iscrizioni ai corsi dalla tabella course_enrollments
        $enrolledCourses = DB::table('course_enrollments')
            ->where('user_id', $user->id)
            ->get()
            ->map(fn($enrollment) => $enrollment->course_id . '|' . $enrollment->teacher_id)
            ->toArray();

            // Recupera tutti i corsi disponibili
            $courses = Course::all();
    
        // Unisce i dati già presenti con quelli recuperati
        $assignedCourses = array_merge($assignedCourses, $enrolledCourses);


        //PER IMMAGINE DI PROFILO INIZIALE
        // Costruisci il nome dell'immagine in base all'iniziale
        $initialImage = 'images/initials/' . $user->initial . '.png';

        // Verifica se l'immagine esiste e se no, usa l'immagine di default
        if (!file_exists(public_path($initialImage))) {
            $initialImage = 'images/initials/default.png';
        }

        $status = User::getStatuses();
        
        // Restituisci la vista con i dettagli dell'utente e i corsi già assegnati
        return view('admin.user.show', compact('user', 'courses', 'assignedCourses', 'roles', 'initialImage', 'status'));

    }
    


    public function getStudentCount(){
        $count = User::whereHas('roles', function ($query) {
                        $query->where('name', 'student'); // Filtro per ruolo "student"
                    })
                    ->count(); // Conta solo gli utenti con il ruolo "student"

        
        return response()->json(['count' => $count]);
    }



    public function getActiveStudentCount(){
        // Esegui la query per ottenere il numero di studenti attivi con ruolo "student"
        $count = User::where('status', 1)  // Utenti attivi
                    ->whereHas('roles', function ($query) {
                        $query->where('name', 'student'); // Filtro per ruolo "student"
                    })
                    ->count();

        // Restituisci il numero come risposta JSON
        return response()->json(['count' => $count]);
    }

    /*public function storeCourses(Request $request, $id){      //FUNZIONA SOLO PER VISUALIZZAZIONE SELEZIONATI

        //dd($request->method(), $request->all()); // Debug per verificare la richiesta
        //dd($request->input('courses'));

        // Trova l'utente nel database
        $user = User::findOrFail($id);

        // Recupera i corsi selezionati
        //$selectedCourses = $request->input('courses', []);

        $selectedCourses = DB::table('teachers_courses')
        ->where('teacher_id', $user->id)
        ->pluck('course_id')
        ->toArray(); // Ottiene un array di course_id associati all'insegnante

        //dd($selectedCourses);

        // Elimina i corsi non selezionati
        DB::table('teachers_courses')
            ->where('teacher_id', $user->id)
            ->whereNotIn('course_id', $selectedCourses)
            ->delete();

            //dd($selectedCourses);

        // Aggiungi i corsi selezionati
        foreach ($selectedCourses as $courseId) {
            if (!DB::table('teachers_courses')->where('teacher_id', $user->id)->where('course_id', $courseId)->exists()) {
                DB::table('teachers_courses')->insert([
                    'teacher_id' => $user->id,
                    'course_id' => $courseId,
                ]);
            }
        }

        //dd($selectedCourses);

        // Redirect o altra logica
        //return redirect()->route('admin.user.show', $id);
        return redirect()->route('admin.user.index')->with('success', 'Corsi aggiornati con successo.');

    }*/


    public  function findTypeUser($id){
        // Trova l'utente nel database
        $user = User::findOrFail($id);
    
        // Verifica se l'utente ha il ruolo di "student" o "teacher"
        if ($user->hasRole('student')) {
            return 'student';
        } elseif ($user->hasRole('teacher')) {
            return 'teacher';
        }
    
        return null; // Nessun ruolo trovato
    }
    
    public function returnPageInBaseOfRole($id){
        $typeUser = $this->findTypeUser($id);
    
        if ($typeUser == 'student') {
            return redirect()->route('admin.user.students');
        } elseif ($typeUser == 'teacher') {
            return redirect()->route('admin.user.teachers');
        }
    
        return redirect()->route('admin.user.index'); // Redirect di default
    
    }


    public function storeCourses(Request $request, $id)
{
    // Trova l'utente
    $user = User::findOrFail($id);

    // Recupera i corsi selezionati
    $selectedCourses = $request->input('courses', []); // Array vuoto se non ci sono corsi selezionati

    // Recupera i corsi già associati a questo insegnante
    $assignedCourses = DB::table('teachers_courses')
        ->where('teacher_id', $user->id)
        ->pluck('course_id') // Ottieni tutti i course_id associati
        ->toArray();

    // Elimina i corsi deselezionati (presenti in $assignedCourses ma non in $selectedCourses)
    $coursesToDelete = array_diff($assignedCourses, $selectedCourses); // Corsi da eliminare

    if (!empty($coursesToDelete)) {
        DB::table('teachers_courses')
            ->where('teacher_id', $user->id)
            ->whereIn('course_id', $coursesToDelete)
            ->delete();
    }

    // Aggiungi i corsi selezionati che non sono ancora associati
    foreach ($selectedCourses as $courseId) {
        if (!in_array($courseId, $assignedCourses)) {
            DB::table('teachers_courses')->insert([
                'teacher_id' => $user->id,
                'course_id' => $courseId,
            ]);
        }
    }

    // Redirect con un messaggio di successo
    return redirect()->route('admin.user.show', $user->id)->with('success', 'Corsi aggiornati con successo.');
}


    /*
        public function storeCourses(Request $request, $id)
{
    // Trova l'utente nel database
    $user = User::findOrFail($id);

    // Recupera i corsi selezionati
    $selectedCourses = $request->input('courses', []);

    // Prepara i dati per l'inserimento
    $coursesToInsert = [];
    foreach ($selectedCourses as $courseId) {
        $coursesToInsert[] = [
            'teacher_id' => $user->id,
            'course_id' => $courseId,
        ];
    }

    dd($coursesToInsert);

    // Inserisce i corsi, ignorando i duplicati
    DB::table('teachers_courses')->insertOrIgnore($coursesToInsert);

    return redirect()->route('admin.user.index')->with('success', 'Corsi aggiornati con successo.');
}
    */

    public function getUserList(Request $request)
{
    // Esegui la query per ottenere gli utenti
    $users = User::all();

    // Decripta il nome degli utenti
    $users->transform(function ($user) {
        $user->name = Crypt::decryptString($user->name); // Decriptazione del nome
        return $user;
    });

    // Restituisci la risposta per DataTables
    return datatables()->of($users)
        ->addColumn('actions', function ($user) {
            // Personalizza la colonna delle azioni come necessario
            return view('admin.user.actions', compact('user'));
        })
        ->make(true);
}


public function updateCourseEnrollments(Request $request, $id)
{
    // Recupera l'utente
    $user = User::findOrFail($id);

    // Recupera i corsi e gli insegnanti selezionati dal form
    $selectedCourses = $request->input('courses', []);
    //dd('Corsi selezionati:', $selectedCourses); // Debug: Verifica i corsi selezionati

    // Estrai gli ID dei corsi selezionati
    $selectedCourseIds = array_map(function ($item) {
        return explode('|', $item)[0]; // Prende solo il course_id
    }, $selectedCourses);
    //dd('ID dei corsi selezionati:', $selectedCourseIds); // Debug: Verifica gli ID dei corsi

    // Elimina le iscrizioni precedenti che non sono più selezionate
    $deleted = DB::table('course_enrollments')
        ->where('user_id', $user->id)
        ->whereNotIn('course_id', $selectedCourseIds)
        ->delete();
    //dd('Iscrizioni eliminate:', $deleted); // Debug: Verifica le iscrizioni eliminate

    // Aggiungi le nuove iscrizioni
    foreach ($selectedCourses as $selected) {
        // Separare il course_id e teacher_id
        list($courseId, $teacherId) = explode('|', $selected);
        //dd('Course ID:', $courseId, 'Teacher ID:', $teacherId); // Debug: Verifica i valori estratti

        // Controlla se l'iscrizione non esiste già
        $exists = DB::table('course_enrollments')
            ->where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->where('teacher_id', $teacherId)
            ->exists();
        //dd('Esiste già?', $exists); // Debug: Verifica se l'iscrizione esiste già

        if (!$exists) {
            // Inserisci l'iscrizione nella tabella
            DB::table('course_enrollments')->insert([
                'user_id' => $user->id,
                'course_id' => $courseId,
                'teacher_id' => $teacherId,
            ]);
            //dd('Inserito:', ['user_id' => $user->id, 'course_id' => $courseId, 'teacher_id' => $teacherId]); // Debug: Verifica l'inserimento
        }
    }

    // Redirect con messaggio di successo
    return redirect()->route('admin.user.show', $user->id)
                     ->with('success', 'Corsi selezionati con successo!');
}

public function students()
{
    return view('admin.user.students');
}

public function teachers()
{
    return view('admin.user.teachers');
}



}
