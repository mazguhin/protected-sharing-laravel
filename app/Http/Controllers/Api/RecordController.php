<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Record\StoreRecord;
use App\Models\Channel;
use App\Models\Recipient;
use App\Services\Record\RecordException;
use App\Services\Record\RecordService;
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
     *          @OA\Property(property="channel_id", type="integer", format="string", example="1"),
     *          @OA\Property(property="minutes", type="integer", format="string", example="15"),
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
     * @param RecordService $recordService
     * @return JsonResponse
     */
    public function store(
        StoreRecord $request,
        RecordService $recordService
    ): JsonResponse
    {
        $recipient = Recipient::find($request->recipient_id);
        $channel = Channel::find($request->channel_id);
        $password = $recordService->generatePassword();

        try {
            $record = $recordService->store($recipient, $channel, $password, array_merge($request->validated(), [
                'author_ip' => $request->getClientIp(),
            ]));

            $recordService->send($record, $password);
        } catch (RecordException $e) {
            return $this->error($e->getMessage());
        } catch (\Exception $e) {
            return $this->error('Возникла ошибка на сервере. Повторите попытку позже.');
        }

        return $this->success([
            'record' => [
                'identifier' => $record->identifier
            ]
        ]);
    }
}
