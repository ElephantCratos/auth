<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Jhon',
            'email' => 'Jhon@gmail.com',
            'password' => Hash::make('pas123'),
            'phone' => '89564645645',
        ]);
    }
}
