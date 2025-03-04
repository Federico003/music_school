<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class TeacherController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
