@extends('layouts.app')

@section('title', 'Start a New Thread · DevDen')

@section('content')
    <section class="mx-auto max-w-3xl">
        <div class="mb-8">
            <p class="forum-eyebrow">Start a discussion</p>
            <h1 class="forum-title mt-2">Create a new thread</h1>
            <p class="forum-copy mt-4">Choose a category, add a clear title, and write a message that invites useful and respectful replies from other members.</p>
        </div>

        <div class="forum-card space-y-6">
            <div>
                <label for="category_id" class="mb-2 block text-sm font-semibold text-[var(--color-muted)]">Category</label>
                <select id="category_id" class="forum-select" disabled>
                    <option>Select a category</option>
                    @foreach ($categories as $category)
                        <option>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="title" class="mb-2 block text-sm font-semibold text-[var(--color-muted)]">Thread title</label>
                <input id="title" class="forum-input" type="text" disabled value="How do you keep an online community active and welcoming?">
            </div>

            <div>
                <label for="body" class="mb-2 block text-sm font-semibold text-[var(--color-muted)]">Body</label>
                <textarea id="body" class="forum-textarea min-h-72 font-mono text-sm" disabled>I would love to hear how other members organize conversations, handle repeat questions, and encourage thoughtful participation as a forum grows.</textarea>
            </div>

            <div class="forum-divider pt-4">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <span class="text-sm text-[var(--color-muted)]">Posting will be available here once discussion publishing is enabled.</span>
                    <div class="flex gap-3">
                        <a href="{{ route('home') }}" class="forum-btn-secondary">Cancel</a>
                        <span class="forum-btn-disabled">Post thread</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
