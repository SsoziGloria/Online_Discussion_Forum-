<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Seed the categories table.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Laravel Architecture',
                'slug' => 'laravel-architecture',
                'description' => 'Laravel ideas, simple patterns, and stories about improving a forum.',
            ],
            [
                'name' => 'Frontend Systems',
                'slug' => 'frontend-systems',
                'description' => 'Layout choices, friendly interfaces, and clear page structure.',
            ],
            [
                'name' => 'Database Design',
                'slug' => 'database-design',
                'description' => 'Keeping data tidy, related, and easy to reason about.',
            ],
            [
                'name' => 'DevOps and Infra',
                'slug' => 'devops-infra',
                'description' => 'Shipping updates, safe releases, and practical rollout notes.',
            ],
            [
                'name' => 'Community Feedback',
                'slug' => 'community-feedback',
                'description' => 'Feature ideas, moderation notes, and general forum conversation.',
            ],
        ];

        foreach ($categories as $attributes) {
            Category::query()->updateOrCreate(
                ['slug' => $attributes['slug']],
                array_merge($attributes, ['thread_count' => 0])
            );
        }
    }
}
