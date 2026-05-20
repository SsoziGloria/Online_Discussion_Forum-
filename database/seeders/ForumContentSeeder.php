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
                'author' => 'gloria_admin',
                'title' => 'When should a Laravel update keep the old sign-in fields?',
                'body' => "I am refreshing a forum app and the login area still feels familiar to a few members.\n\nWould you keep the older sign-in fields around for a while, or switch everything at once and use the new flow everywhere?",
                'is_pinned' => true,
                'is_locked' => false,
                'created_at' => Carbon::now()->subDays(7),
                'posts' => [
                    [
                        'author' => 'elly_notes',
                        'body' => "I would keep them for a short time so people do not feel lost. A gentle transition is usually better than a sudden change.",
                        'created_at' => Carbon::now()->subDays(6)->addHours(2),
                    ],
                    [
                        'author' => 'margaret_mod',
                        'body' => "Compatibility first makes sense here. You can keep the move calm and still make the newer screens feel like part of the same forum.",
                        'created_at' => Carbon::now()->subDays(6)->addHours(5),
                        'is_edited' => true,
                        'edited_at' => Carbon::now()->subDays(5)->addHours(20),
                    ],
                ],
            ],
            [
                'category' => 'community-feedback',
                'author' => 'paulla_reads',
                'title' => 'How do you make seeded forum posts feel natural?',
                'body' => "I like demo data that sounds like real people talking. It helps the forum feel alive instead of looking like a test app.\n\nWhat kind of topics would you use so the posts feel light, friendly, and believable?",
                'is_pinned' => false,
                'is_locked' => false,
                'created_at' => Carbon::now()->subDays(5),
                'posts' => [
                    [
                        'author' => 'robin_lane',
                        'body' => "I would use small everyday topics like favorite forum features, simple Laravel tips, or what makes a discussion easy to read.",
                        'created_at' => Carbon::now()->subDays(5)->addHours(3),
                    ],
                    [
                        'author' => 'gloria_admin',
                        'body' => "That is usually the best kind of seed data. It should read like a real community, even if it is only a sample.",
                        'created_at' => Carbon::now()->subDays(4)->addHours(1),
                    ],
                    [
                        'author' => 'elly_notes',
                        'body' => "A few light Laravel mentions are fine too, as long as the thread still feels like a normal conversation.",
                        'created_at' => Carbon::now()->subDays(4)->addHours(7),
                    ],
                ],
            ],
            [
                'category' => 'frontend-systems',
                'author' => 'elly_notes',
                'title' => 'How far should the guest homepage match the main forum?',
                'body' => "The welcome page should feel like the same place, but it should still stay calm and easy to scan.\n\nWhere do you draw the line between matching the forum and keeping the guest view simple?",
                'is_pinned' => false,
                'is_locked' => false,
                'created_at' => Carbon::now()->subDays(4),
                'posts' => [
                    [
                        'author' => 'paulla_reads',
                        'body' => "I would keep the same colors and button style, but leave the guest page lighter so it does not feel crowded.",
                        'created_at' => Carbon::now()->subDays(4)->addHours(2),
                    ],
                    [
                        'author' => 'lena_hart',
                        'body' => "That balance makes sense. A little Laravel structure is fine in the background, but the front page should still feel friendly first.",
                        'created_at' => Carbon::now()->subDays(3)->addHours(5),
                    ],
                ],
            ],
            [
                'category' => 'devops-infra',
                'author' => 'kai_brooks',
                'title' => 'What should we check before a forum action goes live?',
                'body' => "Some of the forum features are ready, but I still like to pause before switching them on for everyone.\n\nWhat would you check first to make sure the launch feels smooth?",
                'is_pinned' => false,
                'is_locked' => false,
                'created_at' => Carbon::now()->subDays(3),
                'posts' => [
                    [
                        'author' => 'margaret_mod',
                        'body' => "I would check the permissions, make sure the messages are clear, and test the main path once or twice before opening it up.",
                        'created_at' => Carbon::now()->subDays(2)->addHours(1),
                    ],
                ],
            ],
            [
                'category' => 'community-feedback',
                'author' => 'gloria_admin',
                'title' => 'What kind of community posts make a demo forum feel alive?',
                'body' => "The forum looks better when the sample posts sound like real people talking about everyday things.\n\nWhat kinds of light topics would you include so a demo still feels warm and active?",
                'is_pinned' => false,
                'is_locked' => true,
                'created_at' => Carbon::now()->subDays(2),
                'posts' => [
                    [
                        'author' => 'robin_lane',
                        'body' => "I like posts about favorite forum habits, small Laravel wins, or simple things people enjoy while browsing.",
                        'created_at' => Carbon::now()->subDays(2)->addHours(2),
                    ],
                    [
                        'author' => 'paulla_reads',
                        'body' => "Yes, and short posts usually work best. They make the demo feel conversational instead of technical.",
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
