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
                    <div class="mt-4 flex items-start gap-3">
                        <p class="max-w-[68ch] text-lg leading-8 text-[var(--color-muted)]">
                            {{ $member->bio ?: 'This member has not added a public bio yet.' }}
                        </p>
                        @if ((int) auth()->id() === (int) $member->id)
                            <a href="{{ route('settings.profile') }}" class="mt-1 text-[var(--color-primary)] hover:text-[var(--color-primary)]/80 transition" title="Edit settings">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                        @endif
                    </div>
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
            <div>
                <span class="{{ $member->is_banned ? 'forum-action-link-danger' : 'forum-action-link' }}">
                    {{ $member->is_banned ? __('forum.member.status.banned') : __('forum.member.status.active') }}
                </span>
            </div>
            @if ((auth()->user()?->isAdmin() || auth()->user()?->role === 'moderator') && (int) auth()->id() !== (int) $member->id)
                <div class="forum-divider pt-4 space-y-2">
                    @if (auth()->user()?->isAdmin())
                        <form method="POST" action="{{ route('members.role.toggle', $member->username) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="{{ $member->role === 'moderator' ? 'forum-btn-secondary w-full' : 'forum-btn w-full' }}">
                                {{ $member->role === 'moderator' ? __('forum.member.actions.demote_to_user') : __('forum.member.actions.promote_to_moderator') }}
                            </button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('members.ban.toggle', $member->username) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="{{ $member->is_banned ? 'forum-btn-secondary w-full' : 'forum-btn w-full' }}">
                            {{ $member->is_banned ? __('forum.member.actions.unban') : __('forum.member.actions.ban') }}
                        </button>
                    </form>
                </div>
            @endif
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
                    <p class="forum-copy mx-auto mt-3">In case of any activity, this table will populate!</p>
                </div>
            @endforelse
        </div>
    </section>
@endsection
