<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Message;
use App\Utilities\Constants;
use Illuminate\Http\Request;
use App\Services\ChatService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\CreateRoomRequest;
use App\Http\Requests\Chat\SendMessageRequest;
use Symfony\Component\HttpFoundation\Response;

class RoomController extends Controller
{
    private $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    public function createRoom(CreateRoomRequest $request)
    {
        $room = $this->chatService->createRoom($request);

        return checkStatus($room) ?
            sendSuccess(Constants::MESSAGES['room_created'], $room) :
            sendError($room['message'], [], $room['error'], Response::HTTP_FORBIDDEN);
    }

    public function sendMessage(SendMessageRequest $request)
    {
        $message = $this->chatService->sendMessage($request);

        return checkStatus($message) ?
            sendSuccess(Constants::MESSAGES['room_created'], $message) :
            sendError($message['message'], [], $message['error'], Response::HTTP_FORBIDDEN);
    }
}