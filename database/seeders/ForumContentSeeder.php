<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ForumContentSeeder extends Seeder
{
    /**
     * Seed categories, threads, and posts with coherent counters.
     */
    public function run(): void
    {
        $users = User::query()->get()->keyBy('username');
        $categories = Category::query()->get()->keyBy('slug');

        $threads = [
            [
                'category' => 'laravel-architecture',
                'author' => 'sarah_codes',
                'title' => 'When should a Laravel revamp keep the original auth fields?',
                'body' => "I am restructuring a forum schema and added role, username, display name, and moderation metadata.\n\nThe part I keep revisiting is whether I should preserve legacy auth fields during the transition or fully switch the auth flow at the same time. I want the migration path to stay realistic, especially while some pages are still read-only.",
                'is_pinned' => true,
                'is_locked' => false,
                'created_at' => Carbon::now()->subDays(7),
                'posts' => [
                    [
                        'author' => 'grace_schema',
                        'body' => "Keep the legacy fields until registration, profile update, and any seeded defaults are aligned. The migration itself should not become the place where auth silently breaks.",
                        'created_at' => Carbon::now()->subDays(6)->addHours(2),
                    ],
                    [
                        'author' => 'moses_mod',
                        'body' => "The safest pattern is compatibility first, cleanup second. It gives you room to style new pages without coupling all the backend changes to one deploy.",
                        'created_at' => Carbon::now()->subDays(6)->addHours(5),
                        'is_edited' => true,
                        'edited_at' => Carbon::now()->subDays(5)->addHours(20),
                    ],
                ],
            ],
            [
                'category' => 'database-design',
                'author' => 'daniel_query',
                'title' => 'How do you seed realistic forum data without breaking foreign keys?',
                'body' => "I can render the read-only screens once data exists, but I do not want throwaway seed data that ignores relationships.\n\nWhat sequence do you usually use when seeding users, categories, threads, replies, moderation actions, and notifications?",
                'is_pinned' => false,
                'is_locked' => false,
                'created_at' => Carbon::now()->subDays(5),
                'posts' => [
                    [
                        'author' => 'isaac_infra',
                        'body' => "Seed in dependency order and then recalculate any cached counters at the end. That keeps the seeders readable and avoids fragile manual IDs.",
                        'created_at' => Carbon::now()->subDays(5)->addHours(3),
                    ],
                    [
                        'author' => 'amina_admin',
                        'body' => "I usually keep one dedicated seeder for content structure and another for community activity like votes, flags, and notifications. That makes reruns less painful.",
                        'created_at' => Carbon::now()->subDays(4)->addHours(1),
                    ],
                    [
                        'author' => 'nia_product',
                        'body' => "Make the sample content explain the app itself. It helps when reviewing layouts because the text also signals intent.",
                        'created_at' => Carbon::now()->subDays(4)->addHours(7),
                    ],
                ],
            ],
            [
                'category' => 'frontend-systems',
                'author' => 'nia_product',
                'title' => 'How far should a themed auth flow match the main forum shell?',
                'body' => "The default auth pages feel disconnected from the rest of the product.\n\nI want the sign-in and recovery flows to clearly belong to the same forum, but still stay simple enough that they do not compete with the main content screens.",
                'is_pinned' => false,
                'is_locked' => false,
                'created_at' => Carbon::now()->subDays(4),
                'posts' => [
                    [
                        'author' => 'sarah_codes',
                        'body' => "Carry over typography, color, spacing, and button language. That is enough to make the auth flow feel integrated without recreating the whole navigation experience.",
                        'created_at' => Carbon::now()->subDays(4)->addHours(2),
                    ],
                    [
                        'author' => 'grace_schema',
                        'body' => "A distinct guest layout is fine, but it should still inherit the same design tokens and component styling as the forum.",
                        'created_at' => Carbon::now()->subDays(3)->addHours(5),
                    ],
                ],
            ],
            [
                'category' => 'devops-infra',
                'author' => 'isaac_infra',
                'title' => 'What deployment checks would you add before enabling write-side controllers?',
                'body' => "A lot of the interface is already present, but some actions are intentionally read-only.\n\nBefore switching them on, I want a short checklist that covers authorization, seed data quality, and rollback safety.",
                'is_pinned' => false,
                'is_locked' => false,
                'created_at' => Carbon::now()->subDays(3),
                'posts' => [
                    [
                        'author' => 'moses_mod',
                        'body' => "Start with policy coverage, then add smoke tests for create, update, delete, and any moderation paths. If a route exposes user-specific data, lock that down before anything else.",
                        'created_at' => Carbon::now()->subDays(2)->addHours(1),
                    ],
                ],
            ],
            [
                'category' => 'community-feedback',
                'author' => 'amina_admin',
                'title' => 'What should stay visible in read-only previews during production?',
                'body' => "We are still shaping controller behavior, but stakeholders need to review the layouts and real data relationships.\n\nWhat information would you keep exposed in preview mode, and what would you hide until authorization is finished?",
                'is_pinned' => false,
                'is_locked' => true,
                'created_at' => Carbon::now()->subDays(2),
                'posts' => [
                    [
                        'author' => 'nia_product',
                        'body' => "Keep counts, structure, and sample records visible. Hide destructive actions and any flows that can confuse reviewers into thinking they are final.",
                        'created_at' => Carbon::now()->subDays(2)->addHours(2),
                    ],
                    [
                        'author' => 'daniel_query',
                        'body' => "I would also avoid guest fallbacks that leak another user's notifications or settings data. Preview mode still needs boundaries.",
                        'created_at' => Carbon::now()->subDays(1)->addHours(4),
                    ],
                ],
            ],
        ];

        foreach ($threads as $threadData) {
            $thread = Thread::query()->updateOrCreate(
                ['slug' => Str::slug($threadData['title'])],
                [
                    'category_id' => $categories[$threadData['category']]->id,
                    'user_id' => $users[$threadData['author']]->id,
                    'title' => $threadData['title'],
                    'body' => $threadData['body'],
                    'is_pinned' => $threadData['is_pinned'],
                    'is_locked' => $threadData['is_locked'],
                    'reply_count' => count($threadData['posts']),
                    'last_activity_at' => collect($threadData['posts'])->last()['created_at'] ?? $threadData['created_at'],
                    'created_at' => $threadData['created_at'],
                    'updated_at' => collect($threadData['posts'])->last()['created_at'] ?? $threadData['created_at'],
                ]
            );

            foreach ($threadData['posts'] as $index => $postData) {
                Post::query()->updateOrCreate(
                    [
                        'thread_id' => $thread->id,
                        'user_id' => $users[$postData['author']]->id,
                        'created_at' => $postData['created_at'],
                    ],
                    [
                        'body' => $postData['body'],
                        'is_edited' => $postData['is_edited'] ?? false,
                        'edited_at' => $postData['edited_at'] ?? null,
                        'vote_score' => 0,
                        'updated_at' => $postData['edited_at'] ?? $postData['created_at']->copy()->addMinutes($index + 1),
                    ]
                );
            }
        }

        Category::query()->each(function (Category $category): void {
            $category->update([
                'thread_count' => Thread::query()->where('category_id', $category->id)->count(),
            ]);
        });
    }
}
