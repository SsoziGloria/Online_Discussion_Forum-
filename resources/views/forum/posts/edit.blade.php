@extends('layouts.app')

@section('title', 'Edit Reply · DevDen')

@section('content')
    <section class="mx-auto w-full max-w-3xl">
        <div class="forum-card">
            <div class="flex items-center gap-2 text-sm font-semibold uppercase tracking-[0.14em] text-[var(--color-muted)]">
                <span>Edit reply</span>
            </div>

            <h1 class="mt-4 forum-section-title">Revise your response before it goes back into the thread.</h1>
            <p class="mt-4 forum-copy text-base leading-7">
                Editing stays focused on the post body. The reply will remain attached to
                <a href="{{ route('threads.show', $post->thread->slug) }}" class="font-semibold text-[var(--color-primary)] transition hover:text-[var(--color-secondary)]">
                    {{ $post->thread->title }}
                </a>
                in {{ $post->thread->category?->name ?? 'this category' }}.
            </p>

            @if ($errors->any())
                <div class="forum-banner-danger mt-6">
                    Please fix the highlighted issue and try again.
                </div>
            @endif

            <form method="POST" action="{{ route('posts.update', $post) }}" class="mt-8 space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="body" class="forum-label">Reply content</label>
                    <div class="overflow-hidden rounded-2xl border" style="border-color: var(--color-border);">
                        <div class="flex flex-wrap items-center gap-2 border-b px-4 py-3 text-sm" style="border-color: var(--color-border); background: rgba(248, 241, 223, 0.78); color: var(--color-muted);">
                            <span class="forum-tag-neutral">Bold</span>
                            <span class="forum-tag-neutral">Italic</span>
                            <span class="forum-tag-neutral">Lists</span>
                            <span class="forum-tag-neutral">Code</span>
                            <span class="ml-auto">Markdown supported</span>
                        </div>
                        <textarea id="body" name="body" rows="10" class="forum-textarea rounded-none border-0 focus:ring-0">{{ old('body', $post->body) }}</textarea>
                    </div>
                    <x-input-error :messages="$errors->get('body')" class="mt-2" />
                </div>

                <div class="forum-divider pt-4">
                    <div class="flex flex-wrap items-center justify-end gap-3">
                        <a href="{{ route('threads.show', $post->thread->slug) }}" class="forum-btn-secondary">Cancel</a>
                        <button type="submit" class="forum-btn">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
