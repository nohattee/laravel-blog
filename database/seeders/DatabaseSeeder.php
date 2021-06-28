<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleSeeder::class,
        ]);

        User::create([
            'name' => 'Admin',
            'role_id' => 1,
            'email' => 'admin@admin.com',
            'password' => '1qaz@WSX'
        ]);

         \App\Models\User::factory(10)->create([
             'role_id' => 2
         ]);
    }
}
