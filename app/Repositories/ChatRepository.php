<?php

namespace App\Repositories;

use App\Models\DeleteMessage;
use App\Models\Message;
use App\Models\ReadMessage;
use App\Models\Room;
use App\Models\RoomMember;

class ChatRepository
{

    public function create($request)
    {
        $room = Room::create($request);
        $room->members()->create($request);

        return $room->refresh();
    }

    public function findById($id)
    {
        return Room::findOrFail($id);
    }

    public function checkRoomExist($request)
    {
        return Room::whereHas('members', function ($q) use ($request) {
            $q->where(function ($q) use ($request) {
                $q->where([['created_by', auth()->id()], ['user_id', $request->user_id]])
                    ->orWhere([['created_by', $request->user_id], ['user_id', auth()->id()]]);
            });
        })->first();
    }

    public function sendMessage($request)
    {
        return Message::create($request);
    }

    public function getMessages($request)
    {
        $page = $request->page ? $request->page : null;
        return Message::where('room_id', $request->room_id)
            ->when($request->search_item, function ($q) use ($request) {
                $q->whereFullText('message', $request->search_item);
            })
            ->with('sender')
            ->whereDoesntHave('deleteMessage', function ($q) {
                $q->where('deleted_by', auth()->id());
            })->simplePaginate($page);
    }

    public function getChatRoomUsers($request)
    {
        $page = $request->page ? $request->page : null;

        return room::with('lastMessage')->withWhereHas('privateChatMember.createdBy', function ($q) use ($request) {
            $q->when($request->search_item, function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search_item}%");
            })->checkBlockedUser();
        })->withWhereHas('privateChatMember.user', function ($q) use ($request) {
            $q->when($request->search_item, function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search_item}%");
            })->checkBlockedUser();
        })->withCount('unreadMessage')->simplePaginate($page);
    }

    public function readMessage($request)
    {
        return ReadMessage::updateOrCreate(
            ['user_id' => auth()->id()],
            $request
        );
    }

    public function delete($messageId)
    {
        $deleteMessage = DeleteMessage::where('message_id', $messageId)->first();
        if (!$deleteMessage) {
            return DeleteMessage::create([
                'message_id' => $messageId,
                'deleted_by' => auth()->id(),
            ]);
        }
        $deleteMessage->delete();
        $message = Message::find($messageId);

        return $message->delete();
    }
}