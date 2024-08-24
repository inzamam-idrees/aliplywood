<?php

use Illuminate\Database\Seeder;
 use Illuminate\Support\Facades\DB;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'f_name' => "Ali",
            'l_name' => "Admin",
            'image' => "admin-icn.png",
            'email' => 'admin@aliplywood.com',
            'password' => bcrypt('aliplywood@2024'),
        ]);


    }
}
