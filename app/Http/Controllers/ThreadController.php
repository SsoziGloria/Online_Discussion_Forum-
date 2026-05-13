<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    public function index()
    {
        $threads = Thread::with('user', 'posts')->latest()->paginate(10);
        return view('threads.index', compact('threads'));
    }

    public function create()
    {
        return view('threads.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'body' => 'required|min:10',
        ]);

        Thread::create([
            'title' => $request->title,
            'body' => $request->body,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('threads.index')->with('success', 'Thread created successfully!');
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