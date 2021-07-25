<?php

namespace App\Traits;

use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Api Responser Trait
|--------------------------------------------------------------------------
|
| This trait will be used for any response we sent to clients.
|
*/

trait ApiResponser
{
    /**
     * Return a success JSON response.
     *
     * @param  array|string  $data
     * @param  string  $message
     * @param  int|null  $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function success($data = null, int $code = 200)
    {
        $params = [
            'success' => true,
        ];
        if(!empty($data)) {
            $params['data'] = $data;
        }
        return response()->json($params, $code);
    }

    /**
     * Return an error JSON response.
     *
     * @param  string  $message
     * @param  int  $code
     * @param  array|string|null  $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function error(string $message = null, int $code = 400, $data = null)
    {
        $params = [
            'success' => false,
            'code' => $code,
            'message' => $message,
        ];
        if(!empty($data)) {
            $params['data'] = $data;
        }
        return response()->json($params, $code);
    }

}
