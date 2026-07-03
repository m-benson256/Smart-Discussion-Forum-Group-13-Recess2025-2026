<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            [
                'name' => 'Alice Wonderland',
                'email' => 'alice@student.edu',
                'password' => Hash::make('password123'),
                'student_id' => 'STU001',
                'department' => 'Computer Science',
                'year_of_study' => '3rd Year',
                'is_active' => true,
                'warning_count' => 0,
                'is_blacklisted' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bob Builder',
                'email' => 'bob@student.edu',
                'password' => Hash::make('password123'),
                'student_id' => 'STU002',
                'department' => 'Computer Science',
                'year_of_study' => '2nd Year',
                'is_active' => true,
                'warning_count' => 0,
                'is_blacklisted' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Charlie Brown',
                'email' => 'charlie@student.edu',
                'password' => Hash::make('password123'),
                'student_id' => 'STU003',
                'department' => 'Mathematics',
                'year_of_study' => '4th Year',
                'is_active' => true,
                'warning_count' => 1,
                'is_blacklisted' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Diana Prince',
                'email' => 'diana@student.edu',
                'password' => Hash::make('password123'),
                'student_id' => 'STU004',
                'department' => 'Physics',
                'year_of_study' => '1st Year',
                'is_active' => true,
                'warning_count' => 0,
                'is_blacklisted' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Evan Wright',
                'email' => 'evan@student.edu',
                'password' => Hash::make('password123'),
                'student_id' => 'STU005',
                'department' => 'Biology',
                'year_of_study' => '2nd Year',
                'is_active' => true,
                'warning_count' => 0,
                'is_blacklisted' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($students as $student) {
            Student::create($student);
        }

        $this->command->info('✅ Students seeded successfully!');
    }
}