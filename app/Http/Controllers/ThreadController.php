<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ThreadController extends Controller
{
    public function index()
    {
        $threads = Thread::with('user', 'posts')->latest()->paginate(10);
        return view('threads.index', compact('threads'));
    }

    public function create(?Category $category = null)
    {
        return view('threads.create', [
            'category' => $category,
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'body' => 'required|min:10',
            'category_id' => 'required|exists:categories,id',
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
            'user_id' => auth()->id(),
            'title' => $request->title,
            'body' => $request->body,
            'slug' => $slug,
        ]);

        return redirect()->route('threads.show', $thread)->with('success', 'Thread created successfully!');
    }

    public function show(Thread $thread)
    {
        $thread->load('posts.user');
        return view('threads.show', compact('thread'));
    }

    public function edit(Thread $thread)
    {
        if ($thread->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
        return view('threads.edit', compact('thread'));
    }

    public function update(Request $request, Thread $thread)
    {
        if ($thread->user_id !== auth()->id()) {
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
        if ($thread->user_id !== auth()->id()) {
            abort(403);
        }
        $thread->delete();
        return redirect()->route('threads.index')->with('success', 'Thread deleted!');
    }
}