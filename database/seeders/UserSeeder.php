<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(!User::where('email', 'paulorogeriojp@gmail.com')->first()) {
            User::create([
                'name' => 'Paulo Rogério',
                'email' => 'paulorogeriojp@gmail.com',
                'password' => Hash::make('12345678',['rounds' => 12]),
            ]);
        }
        if(!User::where('email', 'gabriel@gmail.com')->first()) {
            User::create([
                'name' => 'Gabriel Alves',
                'email' => 'gabriel@gmail.com',
                'password' => Hash::make('12345678',['rounds' => 12]),
            ]);
        }
        if(!User::where('email', 'gustavo@gmail.com')->first()) {
            User::create([
                'name' => 'Gustavo Alves',
                'email' => 'gustavo@gmail.com',
                'password' => Hash::make('12345678',['rounds' => 12]),
            ]);
        }
    }
}
