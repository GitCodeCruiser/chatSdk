<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'email', 'password', 'number', 'profile_pic', 'option_to_send_message'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function contactLists()
    {
        return $this->hasOne(ContactList::class, 'contact_id', 'id');
    }

    public function unReadMessage()
    {
        return $this->hasOne(ReadMessage::class, 'user_id');
    }

    protected static function scopeCheckBlockedUser($query)
    {
        $id = auth()->id();
        $query->whereNotExists(function ($query) use ($id) {
            $query
                ->select(DB::raw(1))
                ->from('blocked_users')
                ->where(function ($q) use ($id) {
                    $q->whereRaw('blocked_users.blocked_id = users.id')->whereRaw('blocked_users.blocker_id = ' . $id);
                })
                ->orWhere(function ($q) use ($id) {
                    $q->whereRaw('blocked_users.blocker_id = users.id')->whereRaw('blocked_users.blocked_id = ' . $id);
                });
        });
    }
}
