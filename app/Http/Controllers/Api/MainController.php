<?php

namespace App\Http\Controllers\Api;

class MainController extends Controller
{
    /**
     * @OA\Get(
     *      path="/",
     *      operationId="index",
     *      tags={"Common"},
     *      summary="Index",
     *      @OA\Response(
     *        response=200,
     *        description="Успех",
     *        @OA\JsonContent(
     *          @OA\Property(property="success", type="boolean", example="true"),
     *          @OA\Property(
     *              property="data", type="object", example="{}",
     *          )
     *        )
     *      )
     * )
     */
    public function index()
    {
        return $this->success();
    }
}
