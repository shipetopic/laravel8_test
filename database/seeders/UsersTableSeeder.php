<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // # Add 1 specific user
        // \App\Models\User::factory(1)->create(
        //     [
        //         'name' => 'John Doe',
        //         'email' => 'john@laravel.tesz',
        //         'email_verified_at' => now(),
        //         'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        //         'remember_token' => Str::random(10),
        //     ]
        // );

        $usersCount = max((int) $this->command->ask('How many users would you like?', 20), 1);

        # Add 1 specific user - but using 'state' named in this case: "newJohnDoeUser"
        \App\Models\User::factory()->newJohnDoeUser()->create();

        # Add 10 random users
        \App\Models\User::factory($usersCount)->create();
    }
}
