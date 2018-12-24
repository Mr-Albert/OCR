<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Admin',
            'userName' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'default_password' => bcrypt('password'),
        ]);
        $user->assignRole('administrator');

    }
}
