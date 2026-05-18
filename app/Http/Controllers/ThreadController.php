<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Thread;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ThreadController extends Controller
{
    public function index(): View
    {
        $threads = Thread::query()
            ->with(['category', 'user'])
            ->withCount(['posts as replies_count' => fn ($query) => $query->where('is_opening', false)])
            ->orderByDesc('is_pinned')
            ->latest('last_activity_at')
            ->latest()
            ->paginate(12);

        return view('forum.threads.index', [
            'threads' => $threads,
        ]);
    }

    public function create(?Category $category = null): View|RedirectResponse
    {
        if ($category && $category->is_locked) {
            return redirect()->route('categories.show', $category)
                ->with('error', 'This category is locked. You cannot create a new discussion here.');
        }

        return view('forum.threads.create', [
            'category' => $category,
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|min:10|max:255',
            'body' => 'required|string|min:20',
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')->where(fn ($query) => $query->where('is_locked', false)),
            ],
        ]);

        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $counter = 1;

        while (Thread::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        $thread = DB::transaction(function () use ($request, $slug) {
            $thread = Thread::create([
                'category_id' => $request->input('category_id'),
                'user_id' => Auth::id(),
                'title' => $request->title,
                'body' => $request->body,
                'slug' => $slug,
                'last_activity_at' => now(),
            ]);

            $thread->posts()->create([
                'user_id' => Auth::id(),
                'body' => $request->body,
                'is_opening' => true,
            ]);

            $thread->category()->increment('thread_count');

            return $thread;
        });

        return redirect()->route('threads.show', $thread)->with('success', 'Thread created successfully!');
    }

    public function show(Thread $thread): View
    {
        $thread->load([
            'category',
            'user',
            'posts' => function ($query) {
                $query->with(['user', 'votes', 'children.user', 'children.votes'])
                    ->orderBy('created_at');
            },
        ]);

        $openingPost = $thread->posts->firstWhere('is_opening', true)
            ?? $thread->posts->whereNull('parent_id')->first();

        $replies = $thread->posts
            ->where('is_opening', false)
            ->whereNull('parent_id')
            ->values();

        $relatedThreads = Thread::query()
            ->where('category_id', $thread->category_id)
            ->whereKeyNot($thread->id)
            ->with('user')
            ->withCount(['posts as replies_count' => fn ($query) => $query->where('is_opening', false)])
            ->latest('last_activity_at')
            ->take(3)
            ->get();

        return view('forum.threads.show', compact('thread', 'openingPost', 'replies', 'relatedThreads'));
    }

    public function edit(Thread $thread): View
    {
        if ($thread->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('forum.threads.edit', [
            'thread' => $thread,
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Thread $thread): RedirectResponse
    {
        if ($thread->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|min:10|max:255',
            'body' => 'required|string|min:20',
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')->where(fn ($query) => $query->where('is_locked', false)),
            ],
        ]);

        $validated = $request->only(['title', 'body', 'category_id']);
        $previousCategoryId = $thread->category_id;

        DB::transaction(function () use ($thread, $validated, $previousCategoryId) {
            $thread->update($validated);

            if ((int) $previousCategoryId !== (int) $validated['category_id']) {
                Category::whereKey($previousCategoryId)->decrement('thread_count');
                Category::whereKey($validated['category_id'])->increment('thread_count');
            }

            $openingPost = $thread->posts()->where('is_opening', true)->first();

            if ($openingPost) {
                $openingPost->update([
                    'body' => $validated['body'],
                    'is_edited' => true,
                    'edited_at' => now(),
                ]);
            }
        });

        return redirect()->route('threads.show', $thread)->with('success', 'Thread updated!');
    }

    public function destroy(Thread $thread): RedirectResponse
    {
        $actor = Auth::user();
        $canDelete = $actor && ((int) $thread->user_id === (int) $actor->id || $actor->isAdmin());

        if (! $canDelete) {
            abort(403);
        }

        DB::transaction(function () use ($thread) {
            $thread->category()->decrement('thread_count');
            $thread->delete();
        });

        return redirect()->route('home')->with('success', 'Thread deleted!');
    }
}
