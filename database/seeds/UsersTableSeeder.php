<?php

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        Model::unguard();

        // $this->call(UserTableSeeder::class);
        User::query()->truncate(); // truncate user table each time of seeders run

        $users = array(
            [ // create a new user
                'email' => 'admin@admin.com',
                'password' => Hash::make('admin'),
                'name' => 'Administrator'
            ],
            ['name' => 'Ryan Chenkie', 'email' => 'ryanchenkie@gmail.com', 'password' => Hash::make('secret')],
            ['name' => 'Chris Sevilleja', 'email' => 'chris@scotch.io', 'password' => Hash::make('secret')],
            ['name' => 'Holly Lloyd', 'email' => 'holly@scotch.io', 'password' => Hash::make('secret')],
            ['name' => 'Adnan Kukic', 'email' => 'adnan@scotch.io', 'password' => Hash::make('secret')],
        );

        // Loop through each user above and create the record for them in the database
        foreach ($users as $user)
        {
            User::create($user);
        }

        Model::reguard();
    }
}
