<?php

namespace App\Http\Controllers\Api\V1;

use App\Utilities\Constants;
use Illuminate\Http\Request;
use App\Services\ChatService;
use App\Http\Controllers\Controller;
use App\Http\Resources\ChatRoomUserResource;
use App\Http\Requests\Chat\CreateRoomRequest;
use App\Http\Requests\Chat\GetMessageRequest;
use App\Http\Requests\Chat\ReadMessageRequest;
use App\Http\Requests\Chat\SendMessageRequest;
use Symfony\Component\HttpFoundation\Response;

class ChatController extends Controller
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

    public function getMessages(GetMessageRequest $request)
    {
        $message = $this->chatService->getMessages($request);
        return sendSuccess(Constants::MESSAGES['success'], $message);
    }

    public function getChatRoomUsers(Request $request)
    {
        $users = $this->chatService->getChatRoomUsers($request);
        $users = ChatRoomUserResource::collection($users)->response()->getData(true);
        unset($users['meta']);

        return sendSuccess(Constants::MESSAGES['success'], $users);
    }

    public function readMessage(ReadMessageRequest $request)
    {
        $readmessage = $this->chatService->readMessage($request);

        return sendSuccess(Constants::MESSAGES['success'], $readmessage);
    }

    public function deleteMessage($messageId)
    {
        $deleteMessage = $this->chatService->deleteMessage($messageId);

        return checkStatus($deleteMessage) ?
            sendSuccess(Constants::MESSAGES['message_delete'], []) :
            sendError($deleteMessage['message'], [], $deleteMessage['error'], Response::HTTP_FORBIDDEN);
    }
}
