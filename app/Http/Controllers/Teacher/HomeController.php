<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\View\View;

class HomeController 
{
    /**
     * Display the admin dashboard's home page.
     *
     * @return View A view for the admin home page.
     */
    public function index(): View
    {
        return view('teacher.home');
    }
}
