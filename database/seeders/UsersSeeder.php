<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        User::create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
        ]);

        User::create([
            'name' => 'Studente',
            'email' => 'studente@example.com',
            'password' => bcrypt('password'),
        ]);

        User::create([
            'name' => 'Insegnante',
            'email' => 'insegnante@example.com',
            'password' => bcrypt('password'),
        ]);

        User::whereEncrypted('email', 'admin@example.com')->first()->assignRole('admin');
        User::whereEncrypted('email', 'user@example.com')->first()->assignRole('user');
        User::whereEncrypted('email', 'studente@example.com')->first()->assignRole('student');
        User::whereEncrypted('email', 'insegnante@example.com')->first()->assignRole('teacher');
    }
}
