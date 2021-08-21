<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Recipient\AttachChannelToRecipient;
use App\Http\Requests\Api\Recipient\DeleteRecipient;
use App\Http\Requests\Api\Recipient\DetachChannelFromRecipient;
use App\Http\Requests\Api\Recipient\StoreRecipient;
use App\Http\Requests\Api\Recipient\UpdateRecipient;
use App\Http\Resources\Recipient\RecipientResource;
use App\Repositories\ChannelRepository;
use App\Repositories\RecipientRepository;
use App\Services\Recipient\RecipientService;
use Illuminate\Http\JsonResponse;

class RecipientController extends Controller
{
    private RecipientRepository $recipientRepository;
    private RecipientService $recipientService;

    public function __construct(
        RecipientRepository $recipientRepository,
        RecipientService $recipientService
    )
    {
        $this->recipientRepository = $recipientRepository;
        $this->recipientService = $recipientService;
    }

    /**
     * @OA\Get(
     *      path="/recipient",
     *      operationId="getRecipients",
     *      tags={"Recipient"},
     *      summary="Get all recipients",
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
     * @return JsonResponse
     */
    public function getAll(): JsonResponse
    {
        $recipients = $this->recipientRepository->findAll();

        return $this->success([
            'recipients' => RecipientResource::collection($recipients),
        ]);
    }

    /**
     * @OA\Get(
     *      path="/recipient/active",
     *      operationId="getActiveRecipients",
     *      tags={"Recipient"},
     *      summary="Get active recipients",
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
     * @return JsonResponse
     */
    public function getActive(): JsonResponse
    {
        $recipients = $this->recipientRepository->findActive();

        return $this->success([
            'recipients' => RecipientResource::collection($recipients),
        ]);
    }

    /**
     * @OA\Post(
     *      path="/recipient",
     *      operationId="storeRecipient",
     *      tags={"Recipient"},
     *      summary="Store recipient",
     *      @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *          required={"name"},
     *          @OA\Property(property="name", type="string", format="string"),
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
     * @param StoreRecipient $request
     * @return JsonResponse
     */
    public function store(StoreRecipient $request): JsonResponse
    {
        $recipient = null;

        try {
            $recipient = $this->recipientRepository->create($request->validated());
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }

        return $this->success([
            'recipient' => new RecipientResource($recipient),
        ]);
    }

    /**
     * @OA\Post(
     *      path="/recipient/channel",
     *      operationId="attachChannelToRecipient",
     *      tags={"Recipient"},
     *      summary="Attach channel to recipient",
     *      @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *          required={"recipient_id", "channel_id", "data"},
     *          @OA\Property(property="recipient_id", type="integer", format="string"),
     *          @OA\Property(property="channel_id", type="integer", format="string"),
     *          @OA\Property(property="data", type="string", format="string"),
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
     * @param AttachChannelToRecipient $request
     * @param ChannelRepository $channelRepository
     * @return JsonResponse
     */
    public function attachChannel(
        AttachChannelToRecipient $request,
        ChannelRepository $channelRepository
    ): JsonResponse
    {
        $recipient = $this->recipientRepository->findActiveById($request->recipient_id);
        $channel = $channelRepository->findActiveById($request->channel_id);

        $channelIsAttached = $this->recipientService->checkChannelAttached($recipient, $channel);
        if ($channelIsAttached) {
            return $this->error('The channel is already attached');
        }

        try {
            $recipient = $this->recipientService->attachChannel($recipient, $channel, $request->data);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }

        return $this->success([
            'recipient' => new RecipientResource($recipient),
        ]);
    }

    /**
     * @OA\Delete(
     *      path="/recipient/channel",
     *      operationId="detachChannelFromRecipient",
     *      tags={"Recipient"},
     *      summary="Detach channel from recipient",
     *      @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *          required={"recipient_id", "channel_id"},
     *          @OA\Property(property="recipient_id", type="integer", format="string"),
     *          @OA\Property(property="channel_id", type="integer", format="string"),
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
     * @param DetachChannelFromRecipient $request
     * @param ChannelRepository $channelRepository
     * @return JsonResponse
     */
    public function detachChannel(
        DetachChannelFromRecipient $request,
        ChannelRepository $channelRepository
    ):JsonResponse
    {
        $recipient = $this->recipientRepository->findById($request->recipient_id);
        $channel = $channelRepository->findById($request->channel_id);

        try {
            $this->recipientService->detachChannel($recipient, $channel);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }

        return $this->success([
            'recipient' => new RecipientResource($recipient),
        ]);
    }

    /**
     * @OA\Put(
     *      path="/recipient/{id}",
     *      operationId="updateRecipient",
     *      tags={"Recipient"},
     *      summary="Update recipient by id",
     *      @OA\Parameter(
     *          name="id",
     *          description="Recipient ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *          @OA\Property(property="name", type="string", format="string", example="Name"),
     *          @OA\Property(property="is_active", type="integer", format="integer", example="1")
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
     * @param UpdateRecipient $request
     * @return JsonResponse
     */
    public function update(UpdateRecipient $request): JsonResponse
    {
        $recipient = $this->recipientRepository->findById($request->id);

        try {
            $recipient = $this->recipientService->update($recipient, $request->validated());
        } catch (\Exception $e) {
            return $this->error('Возникла ошибка при обновлении получателя');
        }

        return $this->success([
            'recipient' => new RecipientResource($recipient),
        ]);
    }

    /**
     * @OA\Delete(
     *      path="/recipient/{id}",
     *      operationId="deleteRecipient",
     *      tags={"Recipient"},
     *      summary="Delete recipient by id",
     *      @OA\Parameter(
     *          name="id",
     *          description="Recipient ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
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
     * @param DeleteRecipient $request
     * @return JsonResponse
     */
    public function delete(DeleteRecipient $request): JsonResponse
    {
        $recipient = $this->recipientRepository->findById($request->id);

        try {
            $this->recipientService->delete($recipient);
        } catch (\Exception $e) {
            return $this->error('Возникла ошибка при удалении получателя');
        }

        return $this->success();
    }
}
