@extends('layouts.app')

@section('title', 'Moderation Queue · DevDen')

@section('content')
    <section class="mb-8 grid gap-4 md:grid-cols-3">
        <div class="forum-card">
            <p class="forum-eyebrow">Pending flags</p>
            <p class="forum-data mt-2 text-3xl text-[var(--color-primary)]">{{ $pendingCount }}</p>
        </div>
        <div class="forum-card">
            <p class="forum-eyebrow">Resolved flags</p>
            <p class="forum-data mt-2 text-3xl text-[var(--color-primary)]">{{ $resolvedCount }}</p>
        </div>
        <div class="forum-card">
            <p class="forum-eyebrow">Mode</p>
            <p class="mt-2 text-lg text-[var(--color-muted)]">Read-only moderation preview</p>
        </div>
    </section>

    <section>
        <div class="mb-6">
            <p class="forum-eyebrow">Moderator dashboard</p>
            <h1 class="forum-title mt-2">Flagged posts queue</h1>
        </div>

        <div class="space-y-4">
            @forelse ($flags as $flag)
                <article class="forum-card">
                    <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                        <div class="flex-1">
                            <div class="mb-3 flex flex-wrap items-center gap-2">
                                <span class="forum-tag">{{ ucfirst($flag->reason) }}</span>
                                <span class="forum-tag-neutral">{{ ucfirst($flag->status) }}</span>
                                <span class="text-sm text-[var(--color-muted)]">{{ $flag->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-lg leading-8 text-[var(--color-text)]">
                                {{ $flag->post?->body ?: 'The flagged post is no longer available.' }}
                            </p>
                            <div class="mt-4 flex flex-wrap gap-3 text-sm text-[var(--color-muted)]">
                                <span>Reported by {{ $flag->reporter?->display_name ?? $flag->reporter?->username ?? 'Unknown member' }}</span>
                                @if ($flag->post?->thread)
                                    <span>·</span>
                                    <a href="{{ route('threads.show', $flag->post->thread->slug) }}" class="font-semibold text-[var(--color-primary)]">View thread</a>
                                @endif
                            </div>
                        </div>
                        <div class="flex min-w-44 flex-col gap-3">
                            <form method="POST" action="{{ route('flags.dismiss', $flag) }}">
                                @csrf
                                <button type="submit" class="forum-btn-secondary w-full">Dismiss flag</button>
                            </form>
                            <form method="POST" action="{{ route('flags.delete', $flag) }}">
                                @csrf
                                <button type="submit" class="forum-btn-danger w-full">Delete post</button>
                            </form>
                        </div>
                    </div>
                </article>
            @empty
                <div class="forum-card text-center">
                    <p class="forum-section-title">No pending reports</p>
                    <p class="forum-copy mx-auto mt-3">There are currently no unresolved flags waiting for review.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $flags->links() }}
        </div>
    </section>
@endsection
