<?php

namespace App\Http\Controllers;

use App\Models\Flag;
use App\Models\Notification;
use App\Models\Post;
use App\Models\Thread;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PostController extends Controller
{
    public function edit(Post $post): View
    {
        abort_if((int) $post->user_id !== (int) Auth::id(), 403, 'You can only edit your own replies.');

        $post->load(['thread.category', 'user']);

        return view('forum.posts.edit', [
            'post' => $post,
        ]);
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        abort_if((int) $post->user_id !== (int) Auth::id(), 403, 'You can only edit your own replies.');

        $validated = $request->validate([
            'body' => 'required|string|min:1|max:2000',
        ]);

        $post->update([
            'body' => $validated['body'],
            'is_edited' => true,
            'edited_at' => now(),
        ]);

        return redirect()
            ->route('threads.show', $post->thread)
            ->with('success', 'Reply updated successfully.');
    }

    public function store(Request $request, Thread $thread): RedirectResponse
    {
        if ($thread->is_locked) {
            return back()->with('error', 'This thread is locked. New replies are disabled.');
        }

        $user = Auth::user();

        if (! $user) {
            abort(403);
        }

        if ($user->is_banned) {
            return back()->with('error', 'Your account has been banned. Posting replies is disabled.');
        }

        $request->validate([
            'body' => 'required|min:1|max:2000',
            'parent_id' => [
                'nullable',
                'integer',
                Rule::exists('posts', 'id')->where(fn ($query) => $query->where('thread_id', $thread->id)),
            ],
        ]);

        DB::transaction(function () use ($request, $thread) {
            Post::create([
                'body' => $request->body,
                'thread_id' => $thread->id,
                'user_id' => Auth::id(),
                'parent_id' => $request->input('parent_id'),
            ]);

            $thread->forceFill([
                'reply_count' => $thread->reply_count + 1,
                'last_activity_at' => now(),
            ])->save();
        });

        return back()->with('success', 'Reply posted successfully!');
    }

    public function vote(Request $request, Post $post): RedirectResponse
    {
        abort_if($post->user_id === Auth::id(), 403, 'You cannot vote on your own post.');

        $validated = $request->validate([
            'value' => ['required', 'integer', Rule::in([1, -1])],
        ]);

        $value = $validated['value'];
        $author = $post->user;
        $existingVote = Vote::where('user_id', Auth::id())
            ->where('post_id', $post->id)
            ->first();

        if ($existingVote) {
            if ($existingVote->value === $value) {
                $post->vote_score -= $value;
                $author->reputation -= $value;
                $existingVote->delete();

                $post->save();
                $author->save();

                return back()->with('success', 'Your vote has been removed.');
            }

            $difference = $value - $existingVote->value;
            $post->vote_score += $difference;
            $author->reputation += $difference;
            $existingVote->update(['value' => $value]);

            $post->save();
            $author->save();

            if ($value === 1) {
                Notification::create([
                    'user_id' => $author->id,
                    'type' => 'upvote',
                    'data' => [
                        'post_id' => $post->id,
                        'thread_id' => $post->thread_id,
                        'voter_id' => Auth::id(),
                        'voter_name' => Auth::user()->display_name ?? Auth::user()->username,
                        'post_excerpt' => Str::limit($post->body, 120),
                    ],
                ]);
            }

            return back()->with('success', 'Your vote has been updated.');
        }

        Vote::create([
            'user_id' => Auth::id(),
            'post_id' => $post->id,
            'value' => $value,
        ]);

        $post->vote_score += $value;
        $author->reputation += $value;
        $post->save();
        $author->save();

        if ($value === 1) {
            Notification::create([
                'user_id' => $author->id,
                'type' => 'upvote',
                'data' => [
                    'post_id' => $post->id,
                    'thread_id' => $post->thread_id,
                    'voter_id' => Auth::id(),
                    'voter_name' => Auth::user()->display_name ?? Auth::user()->username,
                    'post_excerpt' => Str::limit($post->body, 120),
                ],
            ]);
        }

        return back()->with('success', 'Your vote has been recorded.');
    }

    public function report(Post $post): View
    {
        // Prevent self-reporting
        abort_if($post->user_id === Auth::id(), 403, 'You cannot report your own post.');

        $post->load(['thread.category', 'user']);

        return view('forum.posts.report', [
            'post' => $post,
        ]);
    }

    public function storeReport(Request $request, Post $post): RedirectResponse
    {
        abort_if($post->user_id === Auth::id(), 403);

        $validated = $request->validate([
            'reason' => 'required|in:spam,harassment,misinformation,inappropriate,other',
        ]);

        Flag::updateOrCreate(
            [
                'post_id' => $post->id,
                'reported_by' => Auth::id(),
                'status' => 'pending',
            ],
            [
                'reason' => $validated['reason'],
            ]
        );

        return redirect()
            ->route('threads.show', $post->thread)
            ->with('success', 'Report submitted. Thank you for helping keep the community safe.');
    }

    public function confirmDelete(Post $post): View
    {
        $user = Auth::user();
        $canDelete = $user
            && ((int) $post->user_id === (int) $user->id || $user->isModerator() || $user->isAdmin());

        abort_unless($canDelete, 403, 'You are not allowed to delete this reply.');

        $post->load(['thread.category', 'user']);

        return view('forum.posts.confirm-delete', [
            'post' => $post,
        ]);
    }

    public function destroy(Post $post): RedirectResponse
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (! $user) {
            abort(403);
        }

        $canDelete = (int) $post->user_id === (int) $user->id || $user->isModerator() || $user->isAdmin();

        abort_unless($canDelete, 403, 'You are not allowed to delete this reply.');

        $thread = null;

        DB::transaction(function () use ($post, &$thread) {
            $thread = $post->thread;
            $post->delete();

            if (! $post->is_opening) {
                $thread->decrement('reply_count');
            }
        });

        return redirect()
            ->route('threads.show', $thread)
            ->with('success', 'Post deleted successfully.');
    }

    public function resolveFlag(Flag $flag): RedirectResponse
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (! $user || (! $user->isModerator() && ! $user->isAdmin())) {
            abort(403);
        }

        $flag->update([
            'status' => 'resolved',
            'resolver_id' => Auth::id(),
        ]);

        return back()->with('success', 'Flag has been resolved.');
    }
}
