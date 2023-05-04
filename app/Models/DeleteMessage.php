<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeleteMessage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['message_id', 'room_id', 'deleted_by'];
}
