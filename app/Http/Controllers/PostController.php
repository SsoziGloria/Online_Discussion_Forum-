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
        $validated = $request->validate([
            'body' => 'required|min:1',
            'parent_id' => 'nullable|exists:posts,id',
        ]);

        // if parent_id is provided, ensure it belongs to the same thread
        if (!empty($validated['parent_id'])) {
            $parent = Post::find($validated['parent_id']);
            if (!$parent || $parent->thread_id !== $thread->id) {
                return back()->withErrors(['parent_id' => 'Invalid parent post provided.'])->withInput();
            }
        }

        try {
            Post::create([
                'body' => $validated['body'],
                'thread_id' => $thread->id,
                'user_id' => auth()->id(),
                'parent_id' => $validated['parent_id'] ?? null,
            ]);

            return back()->with('success', 'Reply posted successfully!');
        } catch (\Throwable $e) {
            logger()->error('PostController@store error: '.$e->getMessage(), [
                'exception' => $e,
            ]);

            return back()->withInput()->with('error', 'Could not save reply — server error. Please try again.');
        }
    }

    public function edit(Post $post): View
    {
        $this->ensureOwner($post);

        $post->loadMissing(['thread.category', 'user']);

        return view('forum.posts.edit', [
            'post' => $post,
        ]);
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $this->ensureOwner($post);

        $validated = $request->validate([
            'body' => ['required', 'string', 'min:3'],
        ]);

        $post->update([
            'body' => $validated['body'],
            'is_edited' => true,
            'edited_at' => Carbon::now(),
        ]);

        return redirect()
            ->route('threads.show', $post->thread->slug)
            ->with('success', 'Reply updated successfully.');
    }

    public function confirmDelete(Post $post): View
    {
        $this->ensureOwner($post);

        $post->loadMissing(['thread.category']);

        return view('forum.posts.confirm-delete', [
            'post' => $post,
        ]);
    }

    public function destroy(Post $post): RedirectResponse
    {
        $this->ensureOwner($post);

        $threadSlug = $post->thread->slug;
        $post->delete();

        return redirect()
            ->route('threads.show', $threadSlug)
            ->with('success', 'Reply deleted successfully.');
    }

    public function report(Post $post): View
    {
        abort_if((int) $post->user_id === (int) auth()->id(), 403);

        $post->loadMissing(['thread.category', 'user']);

        return view('forum.posts.report', [
            'post' => $post,
        ]);
    }

    public function storeReport(Request $request, Post $post): RedirectResponse
    {
        abort_if((int) $post->user_id === (int) auth()->id(), 403);

        $validated = $request->validate([
            'reason' => ['required', 'in:spam,harassment,misinformation,inappropriate,other'],
        ]);

        Flag::updateOrCreate(
            [
                'post_id' => $post->id,
                'reported_by' => auth()->id(),
                'status' => 'pending',
            ],
            [
                'reason' => $validated['reason'],
            ]
        );

        return redirect()
            ->route('threads.show', $post->thread->slug)
            ->with('success', 'Report submitted for moderator review.');
    }

    private function ensureOwner(Post $post): void
    {
        abort_if((int) $post->user_id !== (int) auth()->id(), 403);
    }
}