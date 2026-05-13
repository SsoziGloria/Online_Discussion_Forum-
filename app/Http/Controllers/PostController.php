<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Thread;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(Request $request, Thread $thread)
    {
        $request->validate(['body' => 'required|min:3']);

        Post::create([
            'body' => $request->body,
            'thread_id' => $thread->id,
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'Reply posted successfully!');
    }
}