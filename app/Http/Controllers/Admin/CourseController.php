<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CoursesExport;
use App\Http\Requests\CourseStoreRequest;
use App\Http\Requests\CourseUpdateRequest;
use App\Imports\CourseImport;
use App\Models\Course;
use App\Services\CourseService;
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
use Illuminate\Support\Facades\Crypt;
//use App\Exports\CourseExport;

class CourseController extends AdminController
{
    /**
     * The CourseService instance for managing course-related operations.
     */
    protected CourseService $courseService;

    /**
     * CourseService constructor.
     *
     * @param  CourseService  $courseService  The CourseService instance injected for course-related operations.
     */
    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    /**
     * Display a listing of the courses.
     *
     * @return View The view displaying the list of courses.
     */
    public function index(): View
    {
        $courses = Course::all();

        return view('admin.course.index', compact('courses'));
    }

    /**
     * Return the content of the DataTable.
     *
     * @return JsonResponse A JSON response containing DataTable content for courses.
     */
   /*public function list(): JsonResponse
    {
        $courses = Course::all();

        return DataTables::of($courses)
            ->addColumn('actions', function ($course) {
                $buttons = '<span class="mr-1"><a href="course/'.$course->id.'/edit" data-id="'.$course->id.'" class="btn waves-effect btn-sm btn-primary" title="Modifica"><i class="material-icons">edit</i><span>Modifica</span></a></span>';
                $buttons .= '<span class="mr-1"><a href="course/'.$course->id.'/print" data-id="'.$course->id.'" class="btn waves-effect btn-sm btn-default" title="Stampa"><i class="material-icons">print</i><span>Stampa</span></a></span>';
                if (Auth::course()->id !== $course->id) {
                    $buttons .= '<span class="mr-1"><button id="'.$course->id.'" class="btn waves-effect btn-sm btn-danger btn-delete" title="Elimina"><i class="material-icons">delete</i><span>Elimina</span></button></span>';
                }

                return $buttons;
            })
            ->editColumn('rolename', function ($course) {
                return $course->getRoleName();
            })
            ->rawColumns(['actions'])
            ->make(true);
    }*/

    public function list(Request $request)
{
    $courses = Course::query(); // Assicurati che sia corretto

    return DataTables::of($courses)
        ->addColumn('actions', function ($course) {
            // Genera i pulsanti delle azioni
            $buttons = '<span class="mr-1"><a href="course/'.$course->id.'/edit" data-id="'.$course->id.'" class="btn waves-effect btn-sm btn-primary" title="Modifica"><i class="material-icons">edit</i><span>Modifica</span></a></span>';
            $buttons .= '<span class="mr-1"><a href="course/'.$course->id.'/print" data-id="'.$course->id.'" class="btn waves-effect btn-sm btn-default" title="Stampa"><i class="material-icons">print</i><span>Stampa</span></a></span>';
            $buttons .= '<span class="mr-1"><button id="'.$course->id.'" class="btn waves-effect btn-sm btn-danger btn-delete" title="Elimina"><i class="material-icons">delete</i><span>Elimina</span></button></span>';

            return $buttons;
        })
        ->rawColumns(['actions']) // Indica che questa colonna contiene HTML
        ->make(true);
}

    /**
     * Show the form for creating a new course.
     *
     * @return View The view displaying the course creation form.
     */
    public function create(): View
    {
        return view('admin.course.create');
    }

    /**
     * Store a newly created course in storage.
     *
     * @param  CourseStoreRequest  $request  The validated course store request.
     * @return RedirectResponse A redirect response based on the course's role.
     */
    public function store(CourseStoreRequest $request): RedirectResponse
    {
        $validator = $request->validator;

        if (isset($validator) && $validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validatedData = $request->validated();
        $course = $this->courseService->storeCourse($validatedData);

        return Redirect::route(route: 'admin.course.index');
    }

    /**
     * Export courses to an Excel file.
     *
     * @return mixed The Excel download response for exporting courses to a file.
     */
    public function exportToExcel()
    {
        return Excel::download(new CoursesExport, time().'-Corsi.xlsx');
    }

    /**
     * Show the form for Inport courses from an Excel file.
     *
     * @return View The view displaying the course creation form.
     */
    public function showImport()
    {
        return view('admin.course.import');
    }

    /**
     * Show the form for editing the specified course.
     *
     * @param  Course  $course  The course to edit.
     * @return View The view displaying the course edit form.
     */
    public function edit(Course $course): View
    {
        return view('admin.course.edit', compact('course'));
    }

    /**
     * Update the specified course in storage.
     *
     * @param  CourseUpdateRequest  $request  The validated course update request.
     * @param  Course  $course  The course to update.
     * @return RedirectResponse A redirect response to the course index.
     */
    public function update(CourseUpdateRequest $request, Course $course): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);
    
        $course->update($data);

        return Redirect::route('admin.course.index');
    }

    /**
     * Remove the specified course from storage.
     *
     * @param  Course  $course  The course to be removed.
     * @return JsonResponse A JSON response indicating the result of the course removal.
     */
    public function destroy(Course $course): JsonResponse
    {
        return $this->courseService->deleteCourse($course);
    }

    /**
     * Show the form for Inport courses from an Excel file.
     *
     * @return View The view displaying the course creation form.
     */
    public function import(Request $request)
    {
        Excel::import(new CourseImport, $request->file('file'));

        return view('admin.course.import'); // Passa i dati alla vista
    }

    /**
     * Stampa una scheda informativa sull'utente.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*public function print(Course $course)
    {
        try {
            if (! $course) {
                return redirect()->route('admin.course.index')->withErrors("Il corso non esiste.");
            }

            $pdf = PDF::loadView('admin.course.pdf.show', compact('course'));

            return $pdf->download('Course.pdf');
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }*/


    public function print($id){
        try {
            $course = Course::findOrFail($id);

            Log::info('Dati del corso:', [
                'name' => $course->name,
                'description' => $course->description,
                'created_at' => $course->created_at,
            ]);

            $pdf = PDF::loadView('admin.course.pdf.show', compact('course'));

            return $pdf->download('Course.pdf');
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }


public function getCourses(Request $request)
{
    $courses = Course::with('teachers')->get();

    $courses = $courses->map(function ($course) {
        $course->teachers = $course->teachers->map(function ($teacher) {
            try {
                $teacher->name = Crypt::decryptString($teacher->name);
            } catch (\Exception $e) {
                $teacher->name = "Nome non disponibile";
            }
            return $teacher;
        });
        return $course;
    });

    return response()->json($courses);
}


    
}
