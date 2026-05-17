@extends('layouts.app')

@section('title', $thread->title.' · Modern Discourse')

@section('content')
    <section class="grid gap-8 xl:grid-cols-[minmax(0,1fr)_18rem]">
        <div>
            <a href="{{ route('categories.show', $thread->category->slug) }}" class="forum-eyebrow transition hover:text-[var(--color-primary)]">
                Back to {{ $thread->category->name }}
            </a>

            @if (session('success'))
                <div class="forum-banner-success mt-5">
                    {{ session('success') }}
                </div>
            @endif

            <header class="mt-5 forum-card">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <div class="flex flex-wrap gap-2">
                            <span class="forum-tag">{{ $thread->category->name }}</span>
                            @if ($thread->is_locked)
                                <span class="forum-tag-neutral">Locked</span>
                            @endif
                            @if ($thread->is_pinned)
                                <span class="forum-tag-neutral">Pinned</span>
                            @endif
                        </div>
                        <h1 class="mt-4 text-4xl font-extrabold tracking-[-0.04em]">{{ $thread->title }}</h1>
                        <div class="mt-4 flex flex-wrap gap-3 text-sm text-[var(--color-muted)]">
                            <span>{{ $thread->user?->display_name ?? $thread->user?->username ?? 'Unknown member' }}</span>
                            <span>·</span>
                            <span>{{ $thread->created_at->format('M j, Y') }}</span>
                            <span>·</span>
                            <span>{{ $replies->count() }} replies</span>
                        </div>
                    </div>

                    @auth
                        @if ((int) $thread->user_id === (int) auth()->id())
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('threads.edit', $thread) }}" class="forum-action-link">Edit thread</a>
                                <form method="POST" action="{{ route('threads.destroy', $thread) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="forum-action-link-danger" onclick="return confirm('Delete this thread and all its replies?')">
                                        Delete thread
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>
            </header>

            <article class="forum-card mt-6">
                <div class="mb-5 flex items-start justify-between gap-4">
                    <div>
                        <p class="text-lg font-semibold">{{ $openingPost?->user?->display_name ?? $thread->user?->display_name ?? $thread->user?->username ?? 'Unknown member' }}</p>
                        <p class="mt-1 text-sm text-[var(--color-muted)]">
                            Opening post · {{ $openingPost?->created_at?->diffForHumans() ?? $thread->created_at->diffForHumans() }}
                            @if ($openingPost?->is_edited && $openingPost?->edited_at)
                                · edited {{ $openingPost->edited_at->diffForHumans() }}
                            @endif
                        </p>
                    </div>
                    <span class="forum-tag-neutral">Original post</span>
                </div>
                <div class="max-w-[72ch] space-y-4 text-lg leading-8 text-[var(--color-text)]">
                    @foreach (preg_split('/\r\n|\r|\n/', $openingPost?->body ?? $thread->body) as $paragraph)
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
                    <span class="forum-data text-sm text-[var(--color-muted)]">{{ $replies->count() }} top-level</span>
                </div>

                <div class="space-y-4">
                    @forelse ($replies as $post)
                        @include('forum.threads.partials.post', ['post' => $post, 'thread' => $thread, 'depth' => 0])
                    @empty
                        <div class="forum-card text-center">
                            <p class="forum-section-title">No replies yet</p>
                            <p class="forum-copy mx-auto mt-3">Be the first to add a response to this thread.</p>
                        </div>
                    @endforelse
                </div>
            </section>

            @auth
                @if (! $thread->is_locked)
                    <section class="forum-card mt-8">
                        <div class="mb-5 flex items-center justify-between gap-4">
                            <div>
                                <p class="forum-eyebrow">Join the discussion</p>
                                <h2 class="forum-section-title mt-2">Post a reply</h2>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('posts.store', $thread) }}" class="space-y-4">
                            @csrf
                            <textarea
                                name="body"
                                rows="8"
                                class="forum-textarea"
                                placeholder="Add a thoughtful reply to the thread."
                                required
                            >{{ old('parent_id') ? '' : old('body') }}</textarea>
                            <x-input-error :messages="$errors->get('body')" class="mt-2" />

                            <div class="flex items-center justify-between gap-4">
                                <p class="text-sm text-[var(--color-muted)]">Replies are posted directly into this thread.</p>
                                <button type="submit" class="forum-btn">Submit reply</button>
                            </div>
                        </form>
                    </section>
                @else
                    <section class="forum-card mt-8">
                        <div class="forum-banner-danger">
                            This thread is locked. New replies are disabled.
                        </div>
                    </section>
                @endif
            @endauth
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
                        <dd class="forum-data text-sm">{{ $replies->count() }}</dd>
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
                            <a href="{{ route('threads.show', $relatedThread) }}" class="text-base font-semibold leading-6 text-[var(--color-text)] transition hover:text-[var(--color-primary)]">
                                {{ $relatedThread->title }}
                            </a>
                            <p class="mt-1 text-sm text-[var(--color-muted)]">{{ $relatedThread->replies_count }} replies · {{ $relatedThread->created_at->diffForHumans() }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-[var(--color-muted)]">No related threads in this category yet.</p>
                    @endforelse
                </div>
            </div>
        </aside>
    </section>
@endsection
