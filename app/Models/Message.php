<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['sender_id', 'room_id', 'message_type', 'message', 'file_url', 'replay_user_id'];
    protected $appends = ['full_path_url'];

    public function getFullPathUrlAttribute()
    {
        return env('IMAGE_BASE_PATH') . $this->file_url;
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function unreadMessage()
    {
        return $this->hasOne(ReadMessage::class, 'last_message_id');
    }

    public function deleteMessage()
    {
        return $this->hasOne(DeleteMessage::class);
    }
}
