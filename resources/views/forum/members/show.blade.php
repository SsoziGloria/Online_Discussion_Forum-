@extends('layouts.app')

@section('title', ($member->display_name ?? $member->username).' · DevDen')

@section('content')
    <section class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_20rem]">
        <div class="forum-card">
            <div class="flex flex-col gap-6 md:flex-row">
                <div class="forum-avatar h-24 w-24 rounded-[2rem] text-2xl">
                    {{ strtoupper(substr($member->display_name ?? $member->username, 0, 2)) }}
                </div>
                <div class="flex-1">
                    <p class="forum-eyebrow">Public profile</p>
                    <h1 class="mt-2 text-4xl font-extrabold tracking-[-0.04em]">{{ $member->display_name ?? $member->username }}</h1>
                    <p class="mt-1 text-base text-[var(--color-muted)]">{{ '@'.$member->username }} · joined {{ $member->created_at->format('M Y') }}</p>
                    <p class="mt-4 max-w-[68ch] text-lg leading-8 text-[var(--color-muted)]">
                        {{ $member->bio ?: 'This member has not added a public bio yet.' }}
                    </p>
                </div>
            </div>
        </div>

        <aside class="forum-card-muted space-y-4">
            <div>
                <p class="forum-eyebrow">Standing</p>
                <p class="forum-data mt-2 text-3xl text-[var(--color-primary)]">{{ number_format($member->reputation) }}</p>
            </div>
            <div class="forum-divider pt-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-[var(--color-muted)]">Threads</span>
                    <span class="forum-data text-sm">{{ $member->threads_count }}</span>
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-[var(--color-muted)]">Replies</span>
                    <span class="forum-data text-sm">{{ $member->replies_count }}</span>
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-[var(--color-muted)]">Warnings</span>
                    <span class="forum-data text-sm">{{ $member->warnings_count }}</span>
                </div>
            </div>
            <div>
                <span class="forum-tag-neutral">{{ ucfirst($member->role) }}</span>
            </div>
        </aside>
    </section>

    <section class="mt-10">
        <div class="mb-6">
            <p class="forum-eyebrow">Recent activity</p>
            <h2 class="forum-section-title mt-2">Latest threads and replies</h2>
        </div>

        <div class="space-y-4">
            @forelse ($activity as $item)
                <article class="forum-card flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div>
                        <a href="{{ $item['href'] }}" class="text-xl font-semibold tracking-[-0.02em] transition hover:text-[var(--color-primary)]">
                            {{ $item['title'] }}
                        </a>
                        <p class="mt-2 text-sm text-[var(--color-muted)]">
                            {{ $item['meta'] }}
                            @if ($item['category'])
                                · {{ $item['category'] }}
                            @endif
                            · {{ $item['created_at']->diffForHumans() }}
                        </p>
                    </div>
                    <span class="forum-tag-neutral">{{ $item['value'] }}</span>
                </article>
            @empty
                <div class="forum-card text-center">
                    <p class="forum-section-title">No activity yet</p>
                    <p class="forum-copy mx-auto mt-3">The profile page is connected. Activity will appear here from `threads` and `posts` as soon as the member has records.</p>
                </div>
            @endforelse
        </div>
    </section>
@endsection
