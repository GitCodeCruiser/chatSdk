<?php

namespace App\Services;

use App\Utilities\Constants;
use Illuminate\Support\Facades\DB;
use App\Services\UploadFileService;
use App\Repositories\ChatRepository;

class ChatService
{
    private $chatRepository;
    private $uploadFileService;

    public function __construct(ChatRepository $chatRepository, UploadFileService $uploadFileService)
    {
        $this->chatRepository = $chatRepository;
        $this->uploadFileService = $uploadFileService;
    }

    public function createRoom($request)
    {
        DB::beginTransaction();
        try {
            $input = $request->except(['token']);
            $room = $this->chatRepository->checkRoomExist($request);
            if ($room) {
                return $room;
            }
            if ($request->hasFile('profile_pic')) {
                $input['filename'] = $this->uploadFileService->uploadFile($request->profile_pic, Constants::USER_PROFILE_IMAGES_PATH, Constants::USER_PROFILE_IMAGE_PREFIX);
            }
            $input['created_by'] = auth()->id();
            $room =  $this->chatRepository->create($input);

            DB::commit();
            return $room;
        } catch (\Throwable $th) {
            DB::rollBack();
            return sendResponse(null, $th->getMessage());
        }
    }

    public function sendMessage($request)
    {
        DB::beginTransaction();
        try {
            $input = $request->except(['token']);
            if ($request->hasFile('file_url')) {
                $input['file_url'] = storeFiles('chat-images', $request->file_url);
            }
            $input['sender_id'] = auth()->id();
            $message = $this->chatRepository->sendMessage($input);

            DB::commit();
            return $message;
        } catch (\Throwable $th) {
            DB::rollBack();
            return sendResponse(null, $th->getMessage());
        }
    }

    public function getMessages($request)
    {
        return $this->chatRepository->getMessages($request);
    }

    public function getChatRoomUsers($request)
    {
        return $this->chatRepository->getChatRoomUsers($request);
    }

    public function readMessage($request)
    {
        try {
            $input = $request->except(['token']);
            $input['user_id'] = auth()->id();

            return $this->chatRepository->readMessage($input);
        } catch (\Throwable $th) {
            return sendResponse(null, $th->getMessage());
        }
    }

    public function deleteMessage($messageId)
    {
        try {
            return $this->chatRepository->delete($messageId);
        } catch (\Throwable $th) {
            return sendResponse(null, $th->getMessage());
        }
    }
}