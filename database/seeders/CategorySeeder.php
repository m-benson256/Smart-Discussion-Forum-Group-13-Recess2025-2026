<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Computer Science',
                'description' => 'All CS related discussions and quizzes',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mathematics',
                'description' => 'Math topics and assessments',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Physics',
                'description' => 'Physics discussions and quizzes',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Biology',
                'description' => 'Biology and life sciences',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        $this->command->info('✅ Categories seeded successfully!');
    }
}