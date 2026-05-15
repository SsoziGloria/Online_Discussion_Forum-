<?php

namespace App\Http\Controllers;

use App\Models\Flag;
use App\Models\Post;
use App\Models\Thread;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class PostController extends Controller
{
    public function store(Request $request, Thread $thread): RedirectResponse
    {
        $request->validate([
            'body' => 'required|min:3|max:2000',
        ]);

        Post::create([
            'body'      => $request->body,
            'thread_id' => $thread->id,
            'user_id'   => auth()->id(),
        ]);

        return back()->with('success', 'Reply posted successfully!');
    }

    public function report(Post $post): View
    {
        // Prevent self-reporting
        abort_if($post->user_id === auth()->id(), 403, "You cannot report your own post.");

        $post->load(['thread.category', 'user']);

        return view('forum.posts.report', [
            'post' => $post,
        ]);
    }

    public function storeReport(Request $request, Post $post): RedirectResponse
    {
        abort_if($post->user_id === auth()->id(), 403);

        $validated = $request->validate([
            'reason' => 'required|in:spam,harassment,misinformation,inappropriate,other',
        ]);

        Flag::updateOrCreate(
            [
                'post_id'     => $post->id,
                'reported_by' => auth()->id(),
                'status'      => 'pending',
            ],
            [
                'reason' => $validated['reason'],
            ]
        );

        return redirect()
            ->route('threads.show', $post->thread)
            ->with('success', 'Report submitted. Thank you for helping keep the community safe.');
    }

    // ================== Moderation Actions (For Admins & Moderators) ==================

    public function destroy(Post $post): RedirectResponse
    {
        if (!auth()->user()->isModerator() && !auth()->user()->isAdmin()) {
            abort(403, 'Only moderators can delete posts.');
        }

        $threadSlug = $post->thread->slug ?? $post->thread_id;
        $post->delete();

        return redirect()
            ->route('threads.show', $threadSlug)
            ->with('success', 'Post deleted successfully.');
    }

    public function resolveFlag(Flag $flag): RedirectResponse
    {
        if (!auth()->user()->isModerator() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $flag->update([
            'status'      => 'resolved',
            'resolver_id' => auth()->id(),
        ]);

        return back()->with('success', 'Flag has been resolved.');
    }
}