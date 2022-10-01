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
            'name' => 'Hermes Store',
            'firstname' => 'first',
            'middlename' => 'middle',
            'lastname' => 'last',
            'gender' => 'Male',
            'role' => 'Admin',
            'email' => 'hermes@admin.com',
            'password' => Hash::make('hermes@admin.com')
        ]);
    }
}
