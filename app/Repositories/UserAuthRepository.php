<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserAuthRepository
{
    public function signUp($request, $filename, $compress)
    {
        DB::beginTransaction();

        $user = User::create([
            'profile_pic' => $filename,
            'profile_pic_thumbnail' => $compress,
            'name' => $request->name,
            'number' => $request->number,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        DB::commit();
        return $user->number;
    }
}
