<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(!User::where('email', 'teste@teste.com')->first()){
            User::create([

                'name' => 'ddel',
                'email' => 'teste@teste.com',
                'password' => Hash::make('123456a',['rounds' => 12]),

            ]);
        }
        if(!User::where('email', 'promohawk@teste.com')->first()){
            User::create([

                'name' => 'hawk',
                'email' => 'promohawk@teste.com',
                'password' => Hash::make('1234567b',['rounds' => 12]),

            ]);
        }
        if(!User::where('email', 'roberto@teste.com')->first()){
            User::create([

                'name' => 'roberto',
                'email' => 'roberto@teste.com',
                'password' => Hash::make('12345c',['rounds' => 12]),

            ]);
        }
    }
}
