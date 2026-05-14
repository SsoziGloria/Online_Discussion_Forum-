<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

class CommunityController extends Controller
{
    public function show(User $user): View
    {
        $user->loadCount(['threads', 'posts', 'warnings']);

        $recentThreads = Thread::query()
            ->whereBelongsTo($user)
            ->with('category')
            ->withCount('posts')
            ->latest()
            ->take(3)
            ->get()
            ->map(fn (Thread $thread) => [
                'type' => 'thread',
                'title' => $thread->title,
                'href' => route('threads.show', $thread->slug),
                'meta' => 'Started a thread',
                'category' => $thread->category?->name,
                'value' => $thread->posts_count.' replies',
                'created_at' => $thread->created_at,
            ]);

        $recentPosts = $user->posts()
            ->with('thread.category')
            ->latest()
            ->take(3)
            ->get()
            ->map(fn ($post) => [
                'type' => 'post',
                'title' => $post->thread?->title ?? 'Reply',
                'href' => $post->thread ? route('threads.show', $post->thread->slug) : '#',
                'meta' => 'Replied to a thread',
                'category' => $post->thread?->category?->name,
                'value' => number_format($post->vote_score).' score',
                'created_at' => $post->created_at,
            ]);

        /** @var Collection<int, array<string, mixed>> $activity */
        $activity = $recentThreads
            ->concat($recentPosts)
            ->sortByDesc('created_at')
            ->take(6)
            ->values();

        return view('forum.members.show', [
            'member' => $user,
            'activity' => $activity,
        ]);
    }

    public function settings(): View
    {
        $user = auth()->user() ?? User::query()->latest()->first();

        return view('forum.members.settings', [
            'member' => $user,
        ]);
    }
}
