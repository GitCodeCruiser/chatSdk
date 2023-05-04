<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatRoomUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->privateChatMember->createdBy->id == auth()->id()) {
            $user = $this->privateChatMember->user;
        } else {
            $user = $this->privateChatMember->createdBy;
        }
        return [
            "room_id" => $this->id,
            "user" => $user,
            "last_message" => $this->lastMessage
        ];
    }
}