<?php

namespace Database\Seeders;

use App\Models\Flag;
use App\Models\Notification;
use App\Models\Post;
use App\Models\Thread;
use App\Models\User;
use App\Models\Vote;
use App\Models\Warning;
use Illuminate\Database\Seeder;

class CommunityActivitySeeder extends Seeder
{
    /**
     * Seed votes, flags, warnings, and notifications.
     */
    public function run(): void
    {
        $users = User::query()->get()->keyBy('username');
        $threads = Thread::query()->get()->keyBy('slug');

        $paxosReply = Post::query()
            ->where('thread_id', $threads['when-should-a-laravel-update-keep-the-old-sign-in-fields']->id)
            ->where('user_id', $users['elly_notes']->id)
            ->firstOrFail();

        $modReply = Post::query()
            ->where('thread_id', $threads['when-should-a-laravel-update-keep-the-old-sign-in-fields']->id)
            ->where('user_id', $users['margaret_mod']->id)
            ->firstOrFail();

        $seedReply = Post::query()
            ->where('thread_id', $threads['how-do-you-make-seeded-forum-posts-feel-natural']->id)
            ->where('user_id', $users['robin_lane']->id)
            ->firstOrFail();

        $feedbackReply = Post::query()
            ->where('thread_id', $threads['what-kind-of-community-posts-make-a-demo-forum-feel-alive']->id)
            ->where('user_id', $users['paulla_reads']->id)
            ->firstOrFail();

        $votes = [
            [$users['gloria_admin']->id, $paxosReply->id, 1],
            [$users['paulla_reads']->id, $paxosReply->id, 1],
            [$users['robin_lane']->id, $paxosReply->id, 1],
            [$users['elly_notes']->id, $modReply->id, 1],
            [$users['kai_brooks']->id, $modReply->id, 1],
            [$users['gloria_admin']->id, $seedReply->id, 1],
            [$users['elly_notes']->id, $seedReply->id, 1],
            [$users['margaret_mod']->id, $feedbackReply->id, 1],
            [$users['omar_bell']->id, $feedbackReply->id, -1],
        ];

        foreach ($votes as [$userId, $postId, $value]) {
            Vote::query()->updateOrCreate(
                ['user_id' => $userId, 'post_id' => $postId],
                ['value' => $value]
            );
        }

        Post::query()->each(function (Post $post): void {
            $post->update([
                'vote_score' => (int) Vote::query()->where('post_id', $post->id)->sum('value'),
            ]);
        });

        Flag::query()->updateOrCreate(
            [
                'post_id' => $feedbackReply->id,
                'reported_by' => $users['robin_lane']->id,
            ],
            [
                'reason' => 'other',
                'status' => 'pending',
                'resolved_by' => null,
                'resolved_at' => null,
            ]
        );

        Flag::query()->updateOrCreate(
            [
                'post_id' => $seedReply->id,
                'reported_by' => $users['omar_bell']->id,
            ],
            [
                'reason' => 'misinformation',
                'status' => 'resolved',
                'resolved_by' => $users['margaret_mod']->id,
                'resolved_at' => now()->subDay(),
            ]
        );

        Warning::query()->updateOrCreate(
            [
                'user_id' => $users['omar_bell']->id,
                'issued_by' => $users['margaret_mod']->id,
            ],
            [
                'reason' => 'Kept posting sharp replies after a few reminders to keep things friendly.',
            ]
        );

        $notifications = [
            [
                'user_id' => $users['elly_notes']->id,
                'type' => 'reply',
                'data' => ['thread' => $threads['when-should-a-laravel-update-keep-the-old-sign-in-fields']->title],
                'is_read' => false,
            ],
            [
                'user_id' => $users['paulla_reads']->id,
                'type' => 'mention',
                'data' => ['thread' => $threads['how-do-you-make-seeded-forum-posts-feel-natural']->title],
                'is_read' => false,
            ],
            [
                'user_id' => $users['robin_lane']->id,
                'type' => 'upvote',
                'data' => ['thread' => $threads['how-do-you-make-seeded-forum-posts-feel-natural']->title],
                'is_read' => true,
            ],
            [
                'user_id' => $users['gloria_admin']->id,
                'type' => 'reply',
                'data' => ['thread' => $threads['how-far-should-the-guest-homepage-match-the-main-forum']->title],
                'is_read' => true,
            ],
        ];

        foreach ($notifications as $notification) {
            Notification::query()->updateOrCreate(
                [
                    'user_id' => $notification['user_id'],
                    'type' => $notification['type'],
                    'data' => $notification['data'],
                ],
                ['is_read' => $notification['is_read']]
            );
        }
    }
}
