<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;

class StudentDummy extends Seeder
{
    public function run()
    {
        // buat 50 dummy student
        Student::factory()->count(50)->create();
    }
}
