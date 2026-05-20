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
            @if ($notificationOwner)
                <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="forum-btn">Mark all as read</button>
                </form>
            @else
                <span class="forum-btn-disabled">Mark all as read</span>
            @endif
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
                                New reply
                                @if (isset($data['thread']))
                                    in
                                    @if(isset($data['thread_slug']) && $data['thread_slug'])
                                        <a href="{{ route('threads.show', $data['thread_slug']) }}" class="font-semibold hover:underline">{{ $data['thread'] }}</a>
                                    @else
                                        {{ $data['thread'] }}
                                    @endif
                                @endif
                                .
                            @elseif ($notification->type === 'mention')
                                You were mentioned
                                @if (isset($data['thread']))
                                    in
                                    @if(isset($data['thread_slug']) && $data['thread_slug'])
                                        <a href="{{ route('threads.show', $data['thread_slug']) }}" class="font-semibold hover:underline">{{ $data['thread'] }}</a>
                                    @else
                                        {{ $data['thread'] }}
                                    @endif
                                @endif
                                .
                            @else
                                Your contribution received an upvote
                                @if (isset($data['thread']))
                                    @if(isset($data['thread_slug']) && $data['thread_slug'])
                                        in <a href="{{ route('threads.show', $data['thread_slug']) }}" class="font-semibold hover:underline">{{ $data['thread'] }}</a>
                                    @else
                                        in {{ $data['thread'] }}
                                    @endif
                                @endif
                                .
                            @endif
                        </p>
                        <p class="mt-2 text-sm text-[var(--color-muted)]">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        @unless ($notification->is_read)
                            <form action="{{ route('notifications.mark-read', $notification) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-sm text-[var(--color-primary)] hover:underline">Mark as read</button>
                            </form>
                            <span class="h-2.5 w-2.5 rounded-full bg-[var(--color-primary)]"></span>
                        @endunless
                    </div>
                </article>
            @empty
                <div class="forum-card text-center">
                    <p class="forum-section-title">No notifications yet</p>
                    <p class="forum-copy mx-auto mt-3">In case of any, this table will populate!</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $notifications->links() }}
        </div>
    </section>
@endsection
