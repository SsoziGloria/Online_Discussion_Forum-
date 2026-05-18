@extends('layouts.app')

@section('title', $category->name.' · DevDen')

@section('content')
    <section class="flex flex-col gap-5 md:flex-row md:items-end md:justify-between">
        <div>
            <p class="forum-eyebrow">Category listing</p>
            <h1 class="forum-title mt-2">{{ $category->name }}</h1>
            <p class="forum-copy mt-4">{{ $category->description ?: 'Browse recent conversations and discover what members are talking about in this space.' }}</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('home') }}" class="forum-btn-secondary">Back to categories</a>
            <a href="{{ route('threads.create', $category) }}" class="forum-btn">Start a new thread</a>
        </div>
    </section>

    <section class="mt-10 space-y-4">
        @forelse ($threads as $thread)
            <article class="forum-card grid gap-4 lg:grid-cols-[1fr_auto] lg:items-center">
                <div>
                    <div class="mb-3 flex flex-wrap items-center gap-2">
                        @if ($thread->is_pinned)
                            <span class="forum-tag">Pinned</span>
                        @endif
                        @if ($thread->is_locked)
                            <span class="forum-tag-neutral">Locked</span>
                        @endif
                    </div>
                    <a href="{{ route('threads.show', $thread->slug) }}" class="text-2xl font-semibold tracking-[-0.03em] text-[var(--color-text)] transition hover:text-[var(--color-primary)]">
                        {{ $thread->title }}
                    </a>
                    <p class="mt-3 max-w-[70ch] text-base leading-7 text-[var(--color-muted)]">
                        {{ \Illuminate\Support\Str::limit($thread->body, 210) }}
                    </p>
                    <div class="mt-4 flex flex-wrap items-center gap-3 text-sm text-[var(--color-muted)]">
                        <span>{{ $thread->user?->display_name ?? $thread->user?->username ?? 'Unknown member' }}</span>
                        <span>·</span>
                        <span>{{ $thread->created_at->diffForHumans() }}</span>
                        <span>·</span>
                        <span>Last activity {{ $thread->last_activity_at?->diffForHumans() ?? 'not recorded' }}</span>
                    </div>
                </div>
                <div class="forum-card-muted flex min-w-40 items-center justify-between gap-5 p-4 lg:flex-col lg:items-end">
                    <div class="text-right">
                        <p class="forum-eyebrow">Replies</p>
                        <p class="forum-data mt-2 text-2xl text-[var(--color-primary)]">{{ $thread->replies_count }}</p>
                    </div>
                    <div class="flex flex-wrap items-center justify-end gap-2">
                        <a href="{{ route('threads.show', $thread->slug) }}" class="text-sm font-semibold text-[var(--color-primary)] transition hover:opacity-80">Read thread</a>
                        @if (auth()->user()?->isAdmin())
                            <form method="POST" action="{{ route('threads.destroy', $thread) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="forum-action-link-danger" onclick="return confirm('Delete this thread and all its replies?')">
                                    Delete
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </article>
        @empty
            <div class="forum-card text-center">
                <p class="forum-section-title">No threads in this category</p>
                <p class="forum-copy mx-auto mt-3">Once members begin posting here, the latest threads will appear in this category.</p>
            </div>
        @endforelse
    </section>

    <div class="mt-8">
        {{ $threads->links() }}
    </div>
@endsection
