<?php

namespace App\Http\Controllers\User;

use Illuminate\View\View;

class HomeController extends UserController
{
    /**
     * Display the user dashboard's home page.
     *
     * @return View A view for the user home page.
     */
    public function index(): View
    {
        return view('user.home');
    }
}
