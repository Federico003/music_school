<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Course::create([
            'name' => 'Chitarra',
            'description' => 'Corso di chitarra',
        ]);

        Course::create([
            'name' => 'Batteria',
            'description' => 'Corso di batteria',
        ]);

        Course::create([
            'name' => 'Pianoforte',
            'description' => 'Corso di pianoforte',
        ]);

        Course::create([
            'name' => 'Canto',
            'description' => 'Corso di canto',
        ]);
    }
}
