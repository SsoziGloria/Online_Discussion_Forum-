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
                'description' => 'Patterns, tradeoffs, migrations, validation, and long-term maintainability.',
            ],
            [
                'name' => 'Frontend Systems',
                'slug' => 'frontend-systems',
                'description' => 'Design systems, component strategy, accessibility, and interaction design.',
            ],
            [
                'name' => 'Database Design',
                'slug' => 'database-design',
                'description' => 'Schema planning, query structure, indexing, and relational modeling.',
            ],
            [
                'name' => 'DevOps and Infra',
                'slug' => 'devops-infra',
                'description' => 'Deployments, observability, environment safety, queues, and automation.',
            ],
            [
                'name' => 'Community Feedback',
                'slug' => 'community-feedback',
                'description' => 'Product ideas, moderation notes, workflow issues, and UX observations.',
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
