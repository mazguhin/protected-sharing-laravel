<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Recipient\AttachChannelToRecipient;
use App\Http\Requests\Api\Recipient\DetachChannelFromRecipient;
use App\Http\Requests\Api\Recipient\StoreRecipient;
use App\Repositories\ChannelRepository;
use App\Repositories\RecipientRepository;
use App\Services\RecipientService;
use Illuminate\Http\JsonResponse;

class RecipientController extends Controller
{
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
     * @param RecipientRepository $recipientRepository
     * @return JsonResponse
     */
    public function getAll(RecipientRepository $recipientRepository)
    {
        $recipients = $recipientRepository->findAll();

        return $this->success([
            'recipients' => $recipients
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
     * @param RecipientRepository $recipientRepository
     * @return JsonResponse
     */
    public function getActive(RecipientRepository $recipientRepository)
    {
        $recipients = $recipientRepository->findActive();

        return $this->success([
            'recipients' => $recipients
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
     * @param RecipientRepository $recipientRepository
     * @return JsonResponse
     */
    public function store(
        StoreRecipient $request,
        RecipientRepository $recipientRepository
    )
    {
        $recipient = null;

        try {
            $recipient = $recipientRepository->create($request->validated());
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }

        return $this->success([
            'recipient' => $recipient,
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
     * @param RecipientRepository $recipientRepository
     * @param ChannelRepository $channelRepository
     * @param RecipientService $recipientService
     * @return JsonResponse
     */
    public function attachChannel(
        AttachChannelToRecipient $request,
        RecipientRepository $recipientRepository,
        ChannelRepository $channelRepository,
        RecipientService $recipientService
    )
    {
        $recipient = $recipientRepository->findActiveById($request->recipient_id);
        $channel = $channelRepository->findActiveById($request->channel_id);

        $channelIsAttached = $recipientService->checkChannelAttached($recipient, $channel);
        if ($channelIsAttached) {
            return $this->error('The channel is already attached');
        }

        try {
            $recipient = $recipientService->attachChannel($recipient, $channel, $request->data);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }

        return $this->success([
            'recipient' => $recipient,
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
     * @param RecipientRepository $recipientRepository
     * @param ChannelRepository $channelRepository
     * @param RecipientService $recipientService
     * @return JsonResponse
     */
    public function detachChannel(
        DetachChannelFromRecipient $request,
        RecipientRepository $recipientRepository,
        ChannelRepository $channelRepository,
        RecipientService $recipientService
    )
    {
        $recipient = $recipientRepository->findById($request->recipient_id);
        $channel = $channelRepository->findById($request->channel_id);

        try {
            $recipientService->detachChannel($recipient, $channel);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }

        return $this->success([
            'recipient' => $recipient,
        ]);
    }
}
