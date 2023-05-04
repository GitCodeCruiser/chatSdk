<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'Irhan Khalid Saleem',
                'email' => 'irhan.khalid@codingpixel.com',
                'number' => '+923130050039',
                'profile_pic' => null,
                'option_to_send_message' => '1',
                'email_verified_at' => Carbon::now(),
                'password' => bcrypt('12345'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Coding Pixel',
                'email' => 'coding.pixel@codingpixel.com',
                'number' => '+923130050038',
                'profile_pic' => null,
                'option_to_send_message' => '1',
                'email_verified_at' => Carbon::now(),
                'password' => bcrypt('12345'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];
        User::insert($users);
    }
}
