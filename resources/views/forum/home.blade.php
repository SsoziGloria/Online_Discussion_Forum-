@extends('layouts.app')

@section('title', 'DevDen')

@section('content')
    <section class="grid gap-8 lg:grid-cols-[1.15fr_0.85fr]">
        <div class="space-y-6">
            <p class="forum-eyebrow">Read-only community preview</p>
            <h1 class="forum-title">Categories first, threads second, structure always visible.</h1>
            <p class="forum-copy">
                The forum now maps directly to the migrations already in this project: categories contain threads, threads carry the original post body, replies live as posts, and moderation stays visible without enabling writes.
            </p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('threads.create') }}" class="forum-btn">Preview new thread</a>
                <a href="{{ route('search') }}" class="forum-btn-secondary">Search discussions</a>
            </div>
        </div>

        <aside class="forum-card grid gap-4 sm:grid-cols-2">
            <div>
                <p class="forum-eyebrow">Categories</p>
                <p class="forum-data mt-2 text-3xl font-semibold text-[var(--color-primary)]">{{ $stats['categories'] }}</p>
            </div>
            <div>
                <p class="forum-eyebrow">Threads</p>
                <p class="forum-data mt-2 text-3xl font-semibold text-[var(--color-primary)]">{{ $stats['threads'] }}</p>
            </div>
            <div>
                <p class="forum-eyebrow">Replies</p>
                <p class="forum-data mt-2 text-3xl font-semibold text-[var(--color-primary)]">{{ $stats['posts'] }}</p>
            </div>
            <div>
                <p class="forum-eyebrow">Members</p>
                <p class="forum-data mt-2 text-3xl font-semibold text-[var(--color-primary)]">{{ $stats['members'] }}</p>
            </div>
        </aside>
    </section>

    <section class="mt-12">
        <div class="mb-6 flex items-end justify-between gap-4">
            <div>
                <p class="forum-eyebrow">Category map</p>
                <h2 class="forum-section-title mt-2">Discussion spaces built from the existing schema</h2>
            </div>
        </div>

        @if ($categories->isEmpty())
            <div class="forum-card text-center">
                <p class="forum-section-title">No categories yet</p>
                <p class="forum-copy mx-auto mt-3">Run the migrations and seed forum data when ready. The Blade surface is in place and waiting for content.</p>
            </div>
        @else
            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($categories as $category)
                    @php($latestThread = $category->threads->first())
                    <article class="forum-card flex h-full flex-col justify-between">
                        <div>
                            <div class="mb-4 flex items-start justify-between gap-3">
                                <div>
                                    <a href="{{ route('categories.show', $category->slug) }}" class="text-2xl font-semibold tracking-[-0.03em] text-[var(--color-text)] transition hover:text-[var(--color-primary)]">
                                        {{ $category->name }}
                                    </a>
                                    <p class="mt-3 text-base leading-7 text-[var(--color-muted)]">
                                        {{ $category->description ?: 'A discussion area backed by the current forum schema.' }}
                                    </p>
                                </div>
                                <span class="forum-tag-neutral">{{ $category->threads_count }} threads</span>
                            </div>
                        </div>

                        <div class="space-y-4">
                            @if ($latestThread)
                                <div class="forum-card-muted p-4">
                                    <p class="forum-eyebrow">Latest activity</p>
                                    <a href="{{ route('threads.show', $latestThread->slug) }}" class="mt-2 block text-lg font-semibold tracking-[-0.02em] text-[var(--color-primary)] transition hover:opacity-80">
                                        {{ $latestThread->title }}
                                    </a>
                                    <p class="mt-2 text-sm text-[var(--color-muted)]">
                                        {{ $latestThread->user?->display_name ?? $latestThread->user?->username ?? 'Unknown member' }}
                                        · {{ $latestThread->last_activity_at?->diffForHumans() ?? $latestThread->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            @else
                                <div class="forum-card-muted p-4 text-sm text-[var(--color-muted)]">
                                    No threads have been created in this category yet.
                                </div>
                            @endif

                            <div class="flex items-center justify-between">
                                <span class="forum-data text-sm text-[var(--color-muted)]">/{{ $category->slug }}</span>
                                <a href="{{ route('categories.show', $category->slug) }}" class="text-sm font-semibold text-[var(--color-primary)] transition hover:opacity-80">Open category</a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
@endsection
