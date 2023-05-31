<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function successResponse($data, $message = '')
    {
        return $this->apiResponse(200, $data, $message);
    }

    public function errorResponse($data, $message, $code = 0, $error = [])
    {
        return $this->apiResponse($code, $data, $message, $error);
    }

    protected function apiResponse($code, $data, $message, $error = [])
    {
        return \response()->json([
            'result'       => $code,
            'current_time' => time(),
            'message'      => $message,
            'data'         => !empty($data) ? $data : new \stdClass(),
            'error'        => !empty($error) ? $error : new \stdClass()
        ]);
    }
}
