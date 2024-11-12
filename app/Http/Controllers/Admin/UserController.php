<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UsersExport;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Imports\UserImport;
use App\Models\User;
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
        $users = User::all();

        return view('admin.user.index', compact('users'));
    }

    /**
     * Return the content of the DataTable.
     *
     * @return JsonResponse A JSON response containing DataTable content for users.
     */
    public function list(): JsonResponse
    {
        $users = User::all();

        return DataTables::of($users)
            ->addColumn('actions', function ($user) {
                $buttons = '<span class="mr-1"><a href="user/'.$user->id.'/edit" data-id="'.$user->id.'" class="btn waves-effect btn-primary" title="Modifica"><i class="material-icons">edit</i></a></span>';
                $buttons .= '<span class="mr-1"><a href="users/'.$user->id.'/print" data-id="'.$user->id.'" class="btn waves-effect btn-default" title="Stampa"><i class="material-icons">print</i></a></span>';
                if (Auth::user()->id !== $user->id) {
                    $buttons .= '<span class="mr-1"><button id="'.$user->id.'" class="btn waves-effect btn-danger btn-delete" title="Elimina"><i class="material-icons">delete</i></button></span>';
                }

                return $buttons;
            })
            ->editColumn('rolename', function ($user) {
                return $user->getRoleName();
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

    public function import(Request $request)
    {
        Excel::import(new UserImport, $request->file('file'));

        return view('admin.user.import'); // Passa i dati alla vista
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  User  $user  The user to edit.
     * @return View The view displaying the user edit form.
     */
    public function edit(User $user): View
    {
        $roles = User::getRoles();
        $status = User::getStatuses();

        return view('admin.user.edit', compact('user', 'roles', 'status'));
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
        $validator = $request->validator;

        if (isset($validator) && $validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validatedData = $request->validated();
        $this->userService->updateUser($user, $validatedData);
        $this->userService->assignRoleToUser($user, $validatedData['role']);

        return Redirect::route('admin.user.index');
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
}
