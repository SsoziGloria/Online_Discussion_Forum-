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
                            <!-- Dismiss Flag Form -->
                            <form action="{{ route('flags.dismiss', $flag->id) }}" method="POST" class="w-full" id="dismiss-form-{{ $flag->id }}">
                                @csrf
                                @method('PATCH')
                                <div class="mb-3">
                                    <textarea
                                        name="moderator_notes"
                                        placeholder="Reason for dismissal..."
                                        class="w-full rounded border border-[var(--color-border)] bg-[var(--color-surface)] px-3 py-2 text-sm text-[var(--color-text)] placeholder-[var(--color-muted)]"
                                        rows="2"
                                        required></textarea>
                                </div>
                                <button type="submit" class="forum-btn w-full text-center">Dismiss flag</button>
                            </form>

                            <!-- Delete Post Form -->
                            <form action="{{ route('flags.delete-post', $flag->id) }}" method="POST" class="w-full" id="delete-form-{{ $flag->id }}">
                                @csrf
                                @method('DELETE')
                                <div class="mb-3">
                                    <textarea
                                        name="moderator_notes"
                                        placeholder="Reason for deletion..."
                                        class="w-full rounded border border-[var(--color-border)] bg-[var(--color-surface)] px-3 py-2 text-sm text-[var(--color-text)] placeholder-[var(--color-muted)]"
                                        rows="2"
                                        required></textarea>
                                </div>
                                <button
                                    type="submit"
                                    class="inline-flex w-full items-center justify-center rounded-xl px-5 py-3 text-sm font-semibold transition duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98]"
                                    style="background: #dc2626; color: white; box-shadow: 0 10px 24px rgba(220, 38, 38, 0.18);"
                                    onmouseover="this.style.background='#b91c1c'"
                                    onmouseout="this.style.background='#dc2626'"
                                    onclick="return confirm('Are you sure you want to DELETE this post? This action cannot be undone.');">
                                    Delete post
                                </button>
                            </form>
                        </div>
                    </div>
                </article>
            @empty
                <div class="forum-card text-center">
                    <p class="forum-section-title">No flags in the queue</p>
                    <p class="forum-copy mx-auto mt-3">This moderation dashboard is now connected to the `flags` table and will populate when reports exist.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $flags->links() }}
        </div>
    </section>
@endsection
