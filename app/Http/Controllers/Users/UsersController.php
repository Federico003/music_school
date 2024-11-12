<?php

namespace App\Http\Controllers\Users;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class UsersController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
