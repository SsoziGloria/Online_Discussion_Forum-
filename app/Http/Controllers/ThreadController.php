<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ThreadController extends Controller
{
    public function index()
    {
        $threads = Thread::with('user', 'posts')->latest()->paginate(10);
        return view('threads.index', compact('threads'));
    }

    public function create(?Category $category = null)
    {
        if ($category && $category->is_locked) {
            return redirect()->route('categories.show', $category)
                ->with('error', 'This category is locked. You cannot create a new discussion here.');
        }

        return view('threads.create', [
            'category' => $category,
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
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

        $thread = Thread::create([
            'category_id' => $request->input('category_id'),
            'user_id' => Auth::id(),
            'title' => $request->title,
            'body' => $request->body,
            'slug' => $slug,
        ]);

        $thread->posts()->create([
            'user_id' => auth()->id(),
            'body' => $request->body,
            'is_opening' => true,
        ]);

        return redirect()->route('threads.show', $thread)->with('success', 'Thread created successfully!');
    }

    public function show(Thread $thread)
    {
        $thread->load(['posts' => function ($query) {
            $query->with(['user', 'votes', 'children.user', 'children.votes'])->orderBy('created_at');
        }]);

        return view('threads.show', compact('thread'));
    }

    public function edit(Thread $thread)
    {
        if ($thread->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        return view('threads.edit', compact('thread'));
    }

    public function update(Request $request, Thread $thread)
    {
        if ($thread->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|max:255',
            'body' => 'required|min:10',
        ]);

        $thread->update($request->only(['title', 'body']));

        return redirect()->route('threads.show', $thread)->with('success', 'Thread updated!');
    }

    public function destroy(Thread $thread)
    {
        if ($thread->user_id !== Auth::id()) {
            abort(403);
        }
        $thread->delete();
        return redirect()->route('threads.index')->with('success', 'Thread deleted!');
    }
}