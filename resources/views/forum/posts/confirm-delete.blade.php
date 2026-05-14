@extends('layouts.app')

@section('title', 'Delete Reply · DevDen')

@section('content')
    <section class="mx-auto flex min-h-[60vh] w-full max-w-2xl items-center justify-center">
        <div class="forum-card w-full max-w-xl text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full" style="background: rgba(255, 218, 214, 0.6); color: var(--color-danger);">
                <span class="text-3xl font-bold">!</span>
            </div>

            <h1 class="mt-6 forum-section-title">Are you sure you want to delete this reply?</h1>
            <p class="forum-copy mx-auto mt-4 text-base leading-7">
                This action cannot be undone. The reply will be removed from
                <a href="{{ route('threads.show', $post->thread->slug) }}" class="font-semibold text-[var(--color-primary)] transition hover:text-[var(--color-secondary)]">
                    {{ $post->thread->title }}
                </a>
                and will no longer appear in the conversation stream.
            </p>

            <div class="forum-card-muted mt-8 text-left">
                <p class="forum-eyebrow">Reply preview</p>
                <p class="mt-3 text-base leading-7 text-[var(--color-text)]">
                    {{ \Illuminate\Support\Str::limit($post->body, 220) }}
                </p>
            </div>

            <form method="POST" action="{{ route('posts.destroy', $post) }}" class="mt-8 space-y-3">
                @csrf
                @method('DELETE')

                <button type="submit" class="w-full forum-action-link-danger justify-center rounded-xl px-5 py-3">
                    Yes, delete reply
                </button>
                <a href="{{ route('threads.show', $post->thread->slug) }}" class="forum-btn-secondary w-full">
                    Cancel, take me back
                </a>
            </form>
        </div>
    </section>
@endsection
