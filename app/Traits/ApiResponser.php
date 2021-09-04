<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

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
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */
    protected function success($data = [], int $code = 200): JsonResponse
    {
        $params = [
            'success' => true,
        ];
        if (!empty($data)) {
            $params['data'] = $data;
        }
        return response()->json($params, $code);
    }

    /**
     * Return an error JSON response.
     *
     * @param string|null $message
     * @param int $code
     * @param array|null $data
     * @return JsonResponse
     */
    protected function error(string $message = null, int $code = 400, $data = []): JsonResponse
    {
        $params = [
            'success' => false,
            'code' => $code,
            'message' => $message,
        ];
        if (!empty($data)) {
            $params['data'] = $data;
        }
        return response()->json($params, $code);
    }
}
