@extends('layouts.app')

@section('title', $category->name.' · DevDen')

@section('content')
    <section class="mb-8 flex flex-col gap-5 md:flex-row md:items-end md:justify-between">
        <div>
            <p class="forum-eyebrow">Category listing</p>
            <h1 class="forum-title mt-2">{{ $category->name }}</h1>
            <p class="forum-copy mt-4">{{ $category->description ?? 'Browse recent conversations and discover what members are talking about in this space.' }}</p>
            <p class="forum-data mt-3 text-sm text-[var(--color-muted)]">{{ $category->thread_count }} {{ Str::plural('thread', $category->thread_count) }}</p>
        </div>
        <div class="flex flex-wrap gap-3">
            @auth
                <a href="{{ route('threads.create', $category) }}" class="forum-btn">Start a new thread</a>
            @else
                <a href="{{ route('login') }}" class="forum-btn">Login to post</a>
            @endauth
            @can('update', $category)
                <a href="{{ route('categories.edit', $category) }}" class="forum-btn-secondary">Edit category</a>
            @endcan
        </div>
    </section>

    <section class="forum-card mb-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <p class="text-sm text-[var(--color-muted)]">
            Showing {{ $threads->firstItem() ?? 0 }} to {{ $threads->lastItem() ?? 0 }} of {{ $threads->total() }} threads
        </p>
        <div class="flex items-center gap-3">
            <label for="sort-select" class="text-sm font-semibold text-[var(--color-muted)]">Sort by</label>
            <select id="sort-select" class="forum-select min-w-52 py-2">
                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest activity</option>
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest first</option>
                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest first</option>
                <option value="most_replies" {{ request('sort') == 'most_replies' ? 'selected' : '' }}>Most replies</option>
                <option value="most_votes" {{ request('sort') == 'most_votes' ? 'selected' : '' }}>Most votes</option>
            </select>
        </div>
    </section>

    <section class="space-y-4">
        @forelse($threads as $thread)
            <article class="forum-card grid gap-4 lg:grid-cols-[1fr_auto] lg:items-center">
                <div>
                    <div class="mb-3 flex flex-wrap items-center gap-2">
                        @if($thread->is_pinned)
                            <span class="forum-tag">Pinned</span>
                        @endif
                        @if($thread->is_locked)
                            <span class="forum-tag-neutral">Locked</span>
                        @endif
                    </div>

                    <a href="{{ route('threads.show', $thread) }}" class="text-2xl font-semibold tracking-[-0.03em] text-[var(--color-text)] transition hover:text-[var(--color-primary)]">
                        {{ $thread->title }}
                    </a>

                    <div class="mt-4 flex flex-wrap items-center gap-3 text-sm text-[var(--color-muted)]">
                        <span>{{ $thread->user ? $thread->user->username : 'Deleted user' }}</span>
                        <span>·</span>
                        <span>{{ $thread->replies_count ?? 0 }} replies</span>
                        <span>·</span>
                        <span class="forum-data">{{ $thread->vote_score ?? 0 }} votes</span>
                        <span>·</span>
                        <span>{{ $thread->last_activity_at ? $thread->last_activity_at->diffForHumans() : $thread->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                @can('moderate', $thread)
                    <div class="flex justify-end">
                        <a href="{{ route('threads.edit', $thread) }}" class="forum-action-link">Edit thread</a>
                    </div>
                @endcan
            </article>
        @empty
            <div class="forum-card text-center">
                <p class="forum-section-title">No threads in this category</p>
                @auth
                    <a href="{{ route('threads.create', $category) }}" class="forum-link mt-3 inline-flex items-center">Create the first thread</a>
                @else
                    <p class="mt-3 text-sm text-[var(--color-muted)]">
                        <a href="{{ route('login') }}" class="forum-link">Login</a> to start the discussion.
                    </p>
                @endauth
            </div>
        @endforelse
    </section>

    <div class="mt-8">
        {{ $threads->appends(request()->query())->links() }}
    </div>

<script>
document.getElementById('sort-select')?.addEventListener('change', function() {
    const url = new URL(window.location.href);
    url.searchParams.set('sort', this.value);
    window.location.href = url.toString();
});
</script>
@endsection
