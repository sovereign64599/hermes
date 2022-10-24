<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::create([
            'name' => 'Hermes Admin',
            'firstname' => 'first',
            'middlename' => 'middle',
            'lastname' => 'last',
            'gender' => 'Male',
            'role' => 'Admin',
            'email' => 'hermes@admin.com',
            'password' => Hash::make('hermes@admin.com')
        ]);

        \App\Models\User::create([
            'name' => 'Test User',
            'firstname' => 'first',
            'middlename' => 'middle',
            'lastname' => 'last',
            'gender' => 'Male',
            'role' => 'user',
            'email' => 'testuser@test.com',
            'password' => Hash::make('testuser@test.com')
        ]);
    }
}
