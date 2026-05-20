<?php

namespace App\Http\Controllers;

use App\Models\Flag;
use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FlagController extends Controller
{
    /**
     * Dismiss a flag as unfounded, leaving the post unchanged.
     */
    public function dismiss(Request $request, Flag $flag): RedirectResponse
    {
        // Authorize the action
        $this->authorize('resolve', $flag);

        // Validate moderator notes
        $validated = $request->validate([
            'moderator_notes' => 'required|string|max:1000',
        ]);

        // Resolve all flags on this post
        $flag->post->flags()->update([
            'status' => 'resolved',
            'resolved_by' => auth()->id(),
            'resolved_at' => now(),
            'moderator_notes' => $validated['moderator_notes'],
        ]);

        // Notify the reporter if one exists
        if ($flag->reporter_id) {
            Notification::create([
                'user_id' => $flag->reporter_id,
                'type' => 'flag_dismissed',
                'data' => [
                    'post_id' => $flag->post_id,
                    'reason' => $flag->reason,
                    'moderator_notes' => $validated['moderator_notes'],
                    'resolved_by' => auth()->user()->username,
                ],
            ]);
        }

        return redirect()->route('moderation.flags')
            ->with('success', 'Flag dismissed and post left unchanged.');
    }

    /**
     * Delete the flagged post (soft delete), resolving all flags on it.
     */
    public function deletePost(Request $request, Flag $flag): RedirectResponse
    {
        // Authorize the action
        $this->authorize('delete', $flag);

        // Validate moderator notes
        $validated = $request->validate([
            'moderator_notes' => 'required|string|max:1000',
        ]);

        $post = $flag->post;
        $reporter = $flag->reporter;
        $postAuthor = $post->user;

        // Resolve all flags on this post
        $post->flags()->update([
            'status' => 'resolved',
            'resolved_by' => auth()->id(),
            'resolved_at' => now(),
            'moderator_notes' => $validated['moderator_notes'],
        ]);

        // Soft delete the post
        $post->delete();

        // Notify the reporter if one exists
        if ($reporter && $reporter->id) {
            Notification::create([
                'user_id' => $reporter->id,
                'type' => 'flag_post_deleted',
                'data' => [
                    'post_id' => $post->id,
                    'reason' => $flag->reason,
                    'moderator_notes' => $validated['moderator_notes'],
                    'resolved_by' => auth()->user()->username,
                ],
            ]);
        }

        // Notify the post author if different from reporter
        if ($postAuthor && $postAuthor->id !== ($reporter?->id)) {
            Notification::create([
                'user_id' => $postAuthor->id,
                'type' => 'post_deleted_by_moderator',
                'data' => [
                    'post_id' => $post->id,
                    'reason' => $flag->reason,
                    'moderator_notes' => $validated['moderator_notes'],
                    'resolved_by' => auth()->user()->username,
                ],
            ]);
        }

        return redirect()->route('moderation.flags')
            ->with('success', 'Post deleted and all flags resolved.');
    }
}
