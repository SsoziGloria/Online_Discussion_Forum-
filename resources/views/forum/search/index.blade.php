@extends('layouts.app')

@section('title', ($query !== '' ? $query.' · ' : '').'Search · DevDen')

@section('content')
    <section class="mx-auto max-w-5xl">
        <div class="mb-8">
            <p class="forum-eyebrow">Search results</p>
            <h1 class="forum-title mt-2">
                @if ($query !== '')
                    Results for "{{ $query }}"
                @else
                    Search the forum
                @endif
            </h1>
        </div>

        <form action="{{ route('search') }}" method="GET" class="forum-card mb-6 grid gap-4 md:grid-cols-[minmax(0,1fr)_15rem]">
            <input type="search" name="q" value="{{ $query }}" class="forum-input" placeholder="Search by thread title, body, or category">
            <button class="forum-btn" type="submit">Run search</button>
        </form>

        <div class="mb-6 flex flex-wrap gap-2">
            @foreach ($categories as $category)
                <a href="{{ route('categories.show', $category->slug) }}" class="forum-tag-neutral">{{ $category->name }}</a>
            @endforeach
        </div>

        <div class="space-y-4">
            @forelse ($results as $thread)
                <article class="forum-card">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="forum-tag">{{ $thread->category?->name ?? 'General' }}</span>
                        <span class="text-sm text-[var(--color-muted)]">{{ $thread->created_at->diffForHumans() }}</span>
                    </div>
                    <a href="{{ route('threads.show', $thread->slug) }}" class="mt-4 block text-2xl font-semibold tracking-[-0.03em] text-[var(--color-text)] transition hover:text-[var(--color-primary)]">
                        {{ $thread->title }}
                    </a>
                    <p class="mt-3 text-base leading-7 text-[var(--color-muted)]">
                        {{ \Illuminate\Support\Str::limit($thread->body, 260) }}
                    </p>
                    <div class="mt-4 flex flex-wrap gap-3 text-sm text-[var(--color-muted)]">
                        <span>{{ $thread->user?->display_name ?? $thread->user?->username ?? 'Unknown member' }}</span>
                        <span>·</span>
                        <span>{{ $thread->posts_count }} replies</span>
                    </div>
                </article>
            @empty
                <div class="forum-card text-center">
                    <p class="forum-section-title">No matching threads</p>
                    <p class="forum-copy mx-auto mt-3">Try a broader phrase or navigate through categories instead.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $results->links() }}
        </div>
    </section>
@endsection
