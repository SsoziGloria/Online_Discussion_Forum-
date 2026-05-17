@php($depth = $depth ?? 0)
<article class="forum-card {{ $depth > 0 ? 'ml-4 md:ml-10' : '' }}">
    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="text-lg font-semibold">{{ $post->user?->display_name ?? $post->user?->username ?? 'Unknown member' }}</p>
            <p class="mt-1 text-sm text-[var(--color-muted)]">
                {{ $post->created_at->diffForHumans() }}
                @if ($post->is_edited && $post->edited_at)
                    · edited {{ $post->edited_at->diffForHumans() }}
                @endif
            </p>
        </div>
        <span class="forum-data text-sm text-[var(--color-primary)]">{{ $post->vote_score >= 0 ? '+' : '' }}{{ $post->vote_score }}</span>
    </div>

    <p class="mt-4 max-w-[72ch] text-base leading-7 text-[var(--color-text)]">{{ $post->body }}</p>

    <div class="forum-divider mt-5 pt-4">
        <div class="flex flex-wrap items-center gap-3">
            @auth
                <form method="POST" action="{{ route('posts.vote', $post) }}">
                    @csrf
                    <input type="hidden" name="value" value="1">
                    <button type="submit" class="forum-action-link">Upvote</button>
                </form>

                @if ((int) $post->user_id === (int) auth()->id())
                    <a href="{{ route('posts.edit', $post) }}" class="forum-action-link">Edit reply</a>
                    <a href="{{ route('posts.delete.confirm', $post) }}" class="forum-action-link-danger">Delete reply</a>
                @else
                    <a href="{{ route('posts.report', $post) }}" class="forum-action-link">Report reply</a>
                @endif
            @endif
        </div>
    </div>

    @if ($post->children->isNotEmpty())
        <div class="mt-4 space-y-4">
            @foreach ($post->children as $child)
                @include('forum.threads.partials.post', ['post' => $child, 'thread' => $thread, 'depth' => $depth + 1])
            @endforeach
        </div>
    @endif
</article>
