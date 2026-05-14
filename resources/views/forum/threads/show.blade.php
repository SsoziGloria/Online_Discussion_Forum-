@extends('layouts.app')

@section('title', $thread->title.' · DevDen')

@section('content')
    <section class="grid gap-8 xl:grid-cols-[minmax(0,1fr)_18rem]">
        <div>
            <a href="{{ route('categories.show', $thread->category->slug) }}" class="forum-eyebrow transition hover:text-[var(--color-primary)]">
                Back to {{ $thread->category->name }}
            </a>

            <header class="mt-5 forum-card">
                <div class="flex flex-wrap gap-2">
                    <span class="forum-tag">{{ $thread->category->name }}</span>
                    @if ($thread->is_locked)
                        <span class="forum-tag-neutral">Locked</span>
                    @endif
                </div>
                <h1 class="mt-4 text-4xl font-extrabold tracking-[-0.04em]">{{ $thread->title }}</h1>
                <div class="mt-4 flex flex-wrap gap-3 text-sm text-[var(--color-muted)]">
                    <span>{{ $thread->user?->display_name ?? $thread->user?->username ?? 'Unknown member' }}</span>
                    <span>·</span>
                    <span>{{ $thread->created_at->format('M j, Y') }}</span>
                    <span>·</span>
                    <span>{{ $thread->posts->count() }} replies</span>
                </div>
            </header>

            <article class="forum-card mt-6">
                <div class="mb-5 flex items-start justify-between gap-4">
                    <div>
                        <p class="text-lg font-semibold">{{ $thread->user?->display_name ?? $thread->user?->username ?? 'Unknown member' }}</p>
                        <p class="mt-1 text-sm text-[var(--color-muted)]">Original post · {{ $thread->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="forum-tag-neutral">Thread body</span>
                </div>
                <div class="max-w-[72ch] space-y-4 text-lg leading-8 text-[var(--color-text)]">
                    @foreach (preg_split('/\r\n|\r|\n/', $thread->body) as $paragraph)
                        @if (trim($paragraph) !== '')
                            <p>{{ $paragraph }}</p>
                        @endif
                    @endforeach
                </div>
            </article>

            <section class="mt-8">
                <div class="mb-5 flex items-end justify-between gap-4">
                    <div>
                        <p class="forum-eyebrow">Replies</p>
                        <h2 class="forum-section-title mt-2">Conversation stream</h2>
                    </div>
                    <span class="forum-data text-sm text-[var(--color-muted)]">{{ $thread->posts->count() }} total</span>
                </div>

                <div class="space-y-4">
                    @forelse ($thread->posts as $post)
                        <article class="forum-card">
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
                        </article>
                    @empty
                        <div class="forum-card text-center">
                            <p class="forum-section-title">No replies yet</p>
                            <p class="forum-copy mx-auto mt-3">This thread view is now wired. Replies will appear from the `posts` table as soon as data is available.</p>
                        </div>
                    @endforelse
                </div>
            </section>

            <section class="forum-card mt-8">
                <div class="mb-5 flex items-center justify-between gap-4">
                    <div>
                        <p class="forum-eyebrow">Reply preview</p>
                        <h2 class="forum-section-title mt-2">Posting stays disabled for now</h2>
                    </div>
                    <span class="forum-tag-neutral">Read-only</span>
                </div>
                <textarea class="forum-textarea min-h-40" disabled placeholder="Controllers for write actions are intentionally not enabled yet."></textarea>
                <div class="mt-4 flex items-center justify-between gap-4">
                    <p class="text-sm text-[var(--color-muted)]">The page is using the prototype structure, but submit actions remain disabled until create and moderation flows are implemented.</p>
                    <span class="forum-btn-disabled">Submit reply</span>
                </div>
            </section>
        </div>

        <aside class="space-y-4">
            <div class="forum-card-muted">
                <p class="forum-eyebrow">Thread signals</p>
                <dl class="mt-4 space-y-4">
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-[var(--color-muted)]">Created</dt>
                        <dd class="forum-data text-sm">{{ $thread->created_at->format('Y-m-d') }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-[var(--color-muted)]">Replies</dt>
                        <dd class="forum-data text-sm">{{ $thread->posts->count() }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-[var(--color-muted)]">Last activity</dt>
                        <dd class="text-sm">{{ $thread->last_activity_at?->diffForHumans() ?? 'Unknown' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="forum-card-muted">
                <p class="forum-eyebrow">Related threads</p>
                <div class="mt-4 space-y-4">
                    @forelse ($relatedThreads as $relatedThread)
                        <div>
                            <a href="{{ route('threads.show', $relatedThread->slug) }}" class="text-base font-semibold leading-6 text-[var(--color-text)] transition hover:text-[var(--color-primary)]">
                                {{ $relatedThread->title }}
                            </a>
                            <p class="mt-1 text-sm text-[var(--color-muted)]">{{ $relatedThread->posts_count }} replies · {{ $relatedThread->created_at->diffForHumans() }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-[var(--color-muted)]">No related threads in this category yet.</p>
                    @endforelse
                </div>
            </div>
        </aside>
    </section>
@endsection
