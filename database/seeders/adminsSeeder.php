<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
class adminsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert([
            'fname' => 'admin',
            'lname' => 'silwana',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456'),
            'mobile' => '9950612429',
            'role' =>'0',
            'status'=> '1',
            'created_at' => Carbon::now(),
            'created_at' => Carbon::now(),
        ]);
    }
}
