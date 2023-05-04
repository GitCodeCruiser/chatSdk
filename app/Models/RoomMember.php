<?php

namespace App\Models;

use App\Models\Room;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoomMember extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['room_id', 'user_id', 'created_by', 'is_admin'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected function scopeCheckBlockedUser($query)
    {
        $id = auth()->id();
        $query->whereNotExists(function ($query) use ($id) {
            $query
                ->select(DB::raw(1))
                ->from('blocked_users')
                ->where(function ($q) use ($id) {
                    $q->whereRaw('blocked_users.blocked_id = room_members.created_by')->whereRaw('blocked_users.blocker_id = room_members.user_id');
                })
                ->orWhere(function ($q) use ($id) {
                    $q->whereRaw('blocked_users.blocked_id = room_members.user_id')->whereRaw('blocked_users.blocked_id = room_members.created_by');
                });
        });
    }
}
