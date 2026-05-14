<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Flag;
use App\Models\Notification;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    public function home(): View
    {
        $categories = Category::query()
            ->withCount('threads')
            ->with([
                'threads' => fn ($query) => $query
                    ->with(['user', 'posts'])
                    ->withCount('posts')
                    ->latest('last_activity_at')
                    ->latest()
                    ->take(1),
            ])
            ->orderByDesc('threads_count')
            ->orderBy('name')
            ->get();

        return view('forum.home', [
            'categories' => $categories,
            'stats' => [
                'categories' => Category::count(),
                'threads' => Thread::count(),
                'posts' => \App\Models\Post::count(),
                'members' => User::count(),
            ],
        ]);
    }

    public function category(Category $category): View
    {
        $threads = Thread::query()
            ->whereBelongsTo($category)
            ->with(['user', 'category'])
            ->withCount('posts')
            ->orderByDesc('is_pinned')
            ->latest('last_activity_at')
            ->latest()
            ->paginate(8);

        return view('forum.categories.show', [
            'category' => $category,
            'threads' => $threads,
        ]);
    }

    public function thread(Thread $thread): View
    {
        $thread->load([
            'category',
            'user',
            'posts' => fn ($query) => $query->with('user')->orderBy('created_at'),
        ]);

        $relatedThreads = Thread::query()
            ->where('category_id', $thread->category_id)
            ->whereKeyNot($thread->id)
            ->with('user')
            ->withCount('posts')
            ->latest('last_activity_at')
            ->take(3)
            ->get();

        return view('forum.threads.show', [
            'thread' => $thread,
            'relatedThreads' => $relatedThreads,
        ]);
    }

    public function create(): View
    {
        return view('forum.threads.create', [
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    public function search(Request $request): View
    {
        $query = trim((string) $request->string('q'));

        $results = Thread::query()
            ->with(['user', 'category'])
            ->withCount('posts')
            ->when($query !== '', function ($builder) use ($query) {
                $builder->where(function ($inner) use ($query) {
                    $inner->where('title', 'like', "%{$query}%")
                        ->orWhere('body', 'like', "%{$query}%")
                        ->orWhereHas('category', fn ($category) => $category->where('name', 'like', "%{$query}%"));
                });
            })
            ->latest('last_activity_at')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('forum.search.index', [
            'query' => $query,
            'results' => $results,
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    public function notifications(): View
    {
        $user = auth()->user() ?? User::query()->with('notifications')->latest()->first();

        $notifications = $user
            ? $user->notifications()->latest()->paginate(12)
            : Notification::query()->latest()->paginate(12);

        return view('forum.notifications.index', [
            'notificationOwner' => $user,
            'notifications' => $notifications,
        ]);
    }

    public function moderation(): View
    {
        $flags = Flag::query()
            ->with(['post.user', 'post.thread.category', 'reporter', 'resolver'])
            ->latest()
            ->paginate(8);

        return view('forum.moderation.index', [
            'flags' => $flags,
            'pendingCount' => Flag::query()->where('status', 'pending')->count(),
            'resolvedCount' => Flag::query()->where('status', 'resolved')->count(),
        ]);
    }
}
