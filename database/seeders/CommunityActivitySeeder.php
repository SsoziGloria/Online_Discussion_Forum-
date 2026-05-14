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
            ->where('thread_id', $threads['when-should-a-laravel-revamp-keep-the-original-auth-fields']->id)
            ->where('user_id', $users['grace_schema']->id)
            ->firstOrFail();

        $modReply = Post::query()
            ->where('thread_id', $threads['when-should-a-laravel-revamp-keep-the-original-auth-fields']->id)
            ->where('user_id', $users['moses_mod']->id)
            ->firstOrFail();

        $seedReply = Post::query()
            ->where('thread_id', $threads['how-do-you-seed-realistic-forum-data-without-breaking-foreign-keys']->id)
            ->where('user_id', $users['isaac_infra']->id)
            ->firstOrFail();

        $feedbackReply = Post::query()
            ->where('thread_id', $threads['what-should-stay-visible-in-read-only-previews-during-production']->id)
            ->where('user_id', $users['daniel_query']->id)
            ->firstOrFail();

        $votes = [
            [$users['amina_admin']->id, $paxosReply->id, 1],
            [$users['sarah_codes']->id, $paxosReply->id, 1],
            [$users['nia_product']->id, $paxosReply->id, 1],
            [$users['grace_schema']->id, $modReply->id, 1],
            [$users['daniel_query']->id, $modReply->id, 1],
            [$users['amina_admin']->id, $seedReply->id, 1],
            [$users['grace_schema']->id, $seedReply->id, 1],
            [$users['moses_mod']->id, $feedbackReply->id, 1],
            [$users['peter_archived']->id, $feedbackReply->id, -1],
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
                'reported_by' => $users['nia_product']->id,
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
                'reported_by' => $users['peter_archived']->id,
            ],
            [
                'reason' => 'misinformation',
                'status' => 'resolved',
                'resolved_by' => $users['moses_mod']->id,
                'resolved_at' => now()->subDay(),
            ]
        );

        Warning::query()->updateOrCreate(
            [
                'user_id' => $users['peter_archived']->id,
                'issued_by' => $users['moses_mod']->id,
            ],
            [
                'reason' => 'Repeatedly posting disruptive replies and ignoring moderator guidance.',
            ]
        );

        $notifications = [
            [
                'user_id' => $users['sarah_codes']->id,
                'type' => 'reply',
                'data' => ['thread' => $threads['when-should-a-laravel-revamp-keep-the-original-auth-fields']->title],
                'is_read' => false,
            ],
            [
                'user_id' => $users['daniel_query']->id,
                'type' => 'mention',
                'data' => ['thread' => $threads['how-do-you-seed-realistic-forum-data-without-breaking-foreign-keys']->title],
                'is_read' => false,
            ],
            [
                'user_id' => $users['isaac_infra']->id,
                'type' => 'upvote',
                'data' => ['thread' => $threads['how-do-you-seed-realistic-forum-data-without-breaking-foreign-keys']->title],
                'is_read' => true,
            ],
            [
                'user_id' => $users['nia_product']->id,
                'type' => 'reply',
                'data' => ['thread' => $threads['how-far-should-a-themed-auth-flow-match-the-main-forum-shell']->title],
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
