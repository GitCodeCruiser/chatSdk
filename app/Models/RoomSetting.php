<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoomSetting extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['room_id', 'user_id', 'is_muted', 'is_stared'];
}
