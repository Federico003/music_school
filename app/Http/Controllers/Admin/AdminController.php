<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;

class AdminController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public static function makeJsonResponse($data, int $status_code = 200, ?string $message = null): JsonResponse
    {
        $success = $status_code < 400;

        return response()->json([
            'success' => $success,
            'status_code' => $status_code,
            'message' => $message,
            'data' => $data
        ], $status_code);
    }

    public static function makeJsonResponseBadRequest(?string $message = null): JsonResponse
    {
        return self::makeJsonResponse([], 400, is_null($message) ? 'Bad Request' : $message);
    } 

}
