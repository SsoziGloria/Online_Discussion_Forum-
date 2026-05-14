@extends('layouts.app')

@section('title', 'Notifications · DevDen')

@section('content')
    <section class="mx-auto max-w-4xl">
        <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="forum-eyebrow">Notification center</p>
                <h1 class="forum-title mt-2">Your notifications</h1>
                <p class="forum-copy mt-4">
                    @if ($notificationOwner)
                        Showing activity for {{ $notificationOwner->display_name ?? $notificationOwner->username }}.
                    @else
                        No notification owner was resolved, so the system is showing the latest records available.
                    @endif
                </p>
            </div>
            <span class="forum-btn-disabled">Mark all as read</span>
        </div>

        <div class="space-y-3">
            @forelse ($notifications as $notification)
                <article class="forum-card flex items-start gap-4 {{ $notification->is_read ? 'opacity-85' : '' }}">
                    <div class="forum-avatar">
                        @if ($notification->type === 'reply')
                            ↩
                        @elseif ($notification->type === 'mention')
                            @
                        @else
                            ↑
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="text-lg leading-7 text-[var(--color-text)]">
                            @php($data = $notification->data ?? [])
                            @if ($notification->type === 'reply')
                                New reply{{ isset($data['thread']) ? ' in '.$data['thread'] : '' }}.
                            @elseif ($notification->type === 'mention')
                                You were mentioned{{ isset($data['thread']) ? ' in '.$data['thread'] : '' }}.
                            @else
                                Your contribution received an upvote.
                            @endif
                        </p>
                        <p class="mt-2 text-sm text-[var(--color-muted)]">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                    @unless ($notification->is_read)
                        <span class="mt-2 h-2.5 w-2.5 rounded-full bg-[var(--color-primary)]"></span>
                    @endunless
                </article>
            @empty
                <div class="forum-card text-center">
                    <p class="forum-section-title">No notifications yet</p>
                    <p class="forum-copy mx-auto mt-3">The page is wired to the `notifications` table and will populate as soon as records exist.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $notifications->links() }}
        </div>
    </section>
@endsection
