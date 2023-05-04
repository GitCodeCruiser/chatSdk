<?php

namespace App\Models;

use App\Models\Message;
use App\Models\RoomMember;
use App\Models\DeleteMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'profile_pic', 'description', 'type', 'private_chat_status'];

    public function members()
    {
        return $this->hasMany(RoomMember::class, 'room_id');
    }

    public function privateChatMember()
    {
        return $this->hasOne(RoomMember::class, 'room_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'message_id');
    }

    public function lastMessage()
    {
        $userId = auth()->id();

        return $this->hasOne(Message::class)->whereNotIn('id', $this->checkDeleteMessage())
            ->latest();
    }

    public function checkDeleteMessage()
    {
        return DeleteMessage::where('deleted_by', auth()->id())->pluck('message_id');
    }

    public function unreadMessage()
    {
        return $this->hasOne(Message::class)
            ->where(function ($query) {
                $query->where('id', '>', function ($subquery) {
                $subquery->selectRaw('COALESCE(MAX(rm.last_message_id), 0)')
                ->from('read_messages as rm');
            });
            });
    }
}
