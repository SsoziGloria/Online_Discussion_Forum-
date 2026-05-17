@extends('layouts.app')

@section('title', 'Start a New Thread · Modern Discourse')

@section('content')
    <section class="mx-auto max-w-3xl">
        <div class="mb-8">
            <p class="forum-eyebrow">Start a discussion</p>
            <h1 class="forum-title mt-2">Create a new thread</h1>
            <p class="forum-copy mt-4">Choose a category, add a clear title, and write the opening post that will frame the conversation.</p>
        </div>

        @if ($errors->any())
            <div class="forum-banner-danger mb-6">
                Please fix the highlighted fields and try again.
            </div>
        @endif

        <form method="POST" action="{{ route('threads.store') }}" class="forum-card space-y-6">
            @csrf

            <div>
                <label for="category_id" class="forum-label">Category</label>
                @if ($category)
                    <input class="forum-input" type="text" disabled value="{{ $category->name }}">
                    <input type="hidden" name="category_id" value="{{ $category->id }}">
                @else
                    <select id="category_id" name="category_id" class="forum-select" required>
                        <option value="">Select a category</option>
                        @foreach ($categories as $optionCategory)
                            <option
                                value="{{ $optionCategory->id }}"
                                @selected((string) old('category_id') === (string) $optionCategory->id)
                                @disabled($optionCategory->is_locked)
                            >
                                {{ $optionCategory->name }}{{ $optionCategory->is_locked ? ' (Locked)' : '' }}
                            </option>
                        @endforeach
                    </select>
                @endif
                <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
            </div>

            <div>
                <label for="title" class="forum-label">Thread title</label>
                <input
                    id="title"
                    name="title"
                    class="forum-input"
                    type="text"
                    value="{{ old('title') }}"
                    placeholder="Summarize the question or topic clearly"
                    required
                >
                <x-input-error :messages="$errors->get('title')" class="mt-2" />
            </div>

            <div>
                <label for="body" class="forum-label">Opening post</label>
                <textarea
                    id="body"
                    name="body"
                    rows="12"
                    class="forum-textarea font-mono text-sm"
                    placeholder="Write the first post that sets context for the discussion."
                    required
                >{{ old('body') }}</textarea>
                <x-input-error :messages="$errors->get('body')" class="mt-2" />
            </div>

            <div class="forum-divider pt-4">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <p class="text-sm text-[var(--color-muted)]">The submitted body will also become the opening post stored for the thread.</p>
                    <div class="flex gap-3">
                        <a href="{{ $category ? route('categories.show', $category) : route('home') }}" class="forum-btn-secondary">Cancel</a>
                        <button type="submit" class="forum-btn">Post thread</button>
                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection
