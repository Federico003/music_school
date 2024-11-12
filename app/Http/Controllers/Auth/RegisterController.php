<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use App\Services\RegisterService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * The RegisterService instance for managing user-related operations.
     */
    protected RegisterService $userService;

    /**
     * UserService constructor.
     *
     * @param  RegisterService  $userService  The UserService instance injected for user-related operations.
     */
    public function __construct(RegisterService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Show the form for creating a new user.
     *
     * @return View The view displaying the user creation form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  UserRegisterRequest  $request  The validated user store request.
     * @return RedirectResponse A redirect response based on the user's role.
     */
    public function register(UserRegisterRequest $request)
    {
        $validator = $request->validator;

        if (isset($validator) && $validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validatedData = $request->validated();
        $user = $this->userService->storeUser($validatedData);
        $this->userService->assignRoleToUser($user);

        return Redirect::route(route: 'login');
    }
}
