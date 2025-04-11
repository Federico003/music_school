<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class StudentController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

}
