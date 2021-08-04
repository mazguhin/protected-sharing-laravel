<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Record\StoreRecord;
use Illuminate\Http\JsonResponse;

class RecordController extends Controller
{
    /**
     * @OA\Post(
     *      path="/record",
     *      operationId="storeRecord",
     *      tags={"Record"},
     *      summary="Store record",
     *      @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *          required={"data", "recipient_id", "channel_id"},
     *          @OA\Property(property="data", type="string", format="string", example="text"),
     *          @OA\Property(property="recipient_id", type="integer", format="string", example="1"),
     *          @OA\Property(property="channel_id", type="integer", format="string", example="2"),
     *        )
     *      ),
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
     * @param StoreRecord $request
     * @return JsonResponse
     */
    public function store(
        StoreRecord $request
    )
    {
        return $this->success([]);
    }
}
