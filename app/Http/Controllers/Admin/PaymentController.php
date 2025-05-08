<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;


class PaymentController extends AdminController
{
    /**
     * Display a listing of the courses.
     *
     * @return View The view displaying the list of courses.
     */
    public function index(): View
    {
        return view('admin.payment.index');
    }
    
}
