<?php

namespace App\Models;

class App
{
    public static function makeSuccessResponse($data = [])
    {
        return response()->json(array_merge(['success' => true], $data));
    }

    public static function makeErrorResponse($code = 500, $data = [], $msg = '')
    {
        return response()->json(array_merge(['success' => false, 'code' => $code, 'message' => $msg], $data));
    }
}
