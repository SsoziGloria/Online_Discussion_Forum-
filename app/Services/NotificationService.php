<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Post;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Str;

class NotificationService
{
    /**
     * Create reply notifications for the thread author and parent post author.
     * Suppresses self-notifications.
     */
    public static function createReplyNotification(Post $post, Thread $thread): void
    {
        $userId = $post->user_id;

        // Notify the thread author if they're not the reply author
        if ($thread->user_id !== $userId) {
            Notification::create([
                'user_id' => $thread->user_id,
                'type' => 'reply',
                'data' => [
                    'thread' => $thread->title,
                    'thread_id' => $thread->id,
                    'thread_slug' => $thread->slug ?? null,
                    'post_id' => $post->id,
                    'author_name' => $post->user->display_name ?? $post->user->username,
                    'post_excerpt' => Str::limit($post->body, 120),
                ],
            ]);
        }

        // If replying to a specific post (not the thread itself), notify the parent post author
        if ($post->parent_id) {
            $parentPost = Post::find($post->parent_id);
            if ($parentPost && $parentPost->user_id !== $userId && $parentPost->user_id !== $thread->user_id) {
                Notification::create([
                    'user_id' => $parentPost->user_id,
                    'type' => 'reply',
                    'data' => [
                        'thread' => $thread->title,
                        'thread_id' => $thread->id,
                        'thread_slug' => $thread->slug ?? null,
                        'post_id' => $post->id,
                        'author_name' => $post->user->display_name ?? $post->user->username,
                        'post_excerpt' => Str::limit($post->body, 120),
                    ],
                ]);
            }
        }
    }

    /**
     * Create a single mention notification for all mentioned users.
     * Suppresses self-mentions.
     */
    public static function createMentionNotification(Post $post, array|EloquentCollection $mentionedUsers): void
    {
        // Normalize input: allow either an array of usernames, an array of User models,
        // or an Eloquent Collection of User models.
        if (is_array($mentionedUsers)) {
            // If array contains plain usernames (strings), resolve them to users.
            if (count($mentionedUsers) > 0 && is_string($mentionedUsers[0])) {
                $mentionedUsers = User::query()->whereIn('username', $mentionedUsers)->get();
            } else {
                $mentionedUsers = collect($mentionedUsers);
            }
        }

        if ($mentionedUsers instanceof EloquentCollection && $mentionedUsers->isEmpty()) {
            return;
        }

        $userId = $post->user_id;
        $usernames = $mentionedUsers->pluck('username')->toArray();

        foreach ($mentionedUsers as $mentionedUser) {
            // If the collection item is a username string (unlikely here), skip it.
            if (is_string($mentionedUser)) {
                continue;
            }

            // Suppress self-mentions
            if ($mentionedUser->id === $userId) {
                continue;
            }

            Notification::create([
                'user_id' => $mentionedUser->id,
                'type' => 'mention',
                'data' => [
                    'thread' => $post->thread->title,
                    'thread_id' => $post->thread_id,
                    'thread_slug' => $post->thread->slug ?? null,
                    'post_id' => $post->id,
                    'author_name' => $post->user->display_name ?? $post->user->username,
                    'mentioned_users' => $usernames,
                    'post_excerpt' => Str::limit($post->body, 120),
                ],
            ]);
        }
    }
}
