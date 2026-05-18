@extends('layouts.app')

@section('title', 'Create category · DevDen')

@section('content')
    <section class="mx-auto max-w-3xl">
        <div class="mb-8">
            <p class="forum-eyebrow">Category setup</p>
            <h1 class="forum-title mt-2">Create a category</h1>
            <p class="forum-copy mt-4">Add a clear name and description so members can quickly understand what conversations belong here.</p>
        </div>

        @if ($errors->any())
            <div class="forum-banner-danger mb-6">Please fix the highlighted fields and try again.</div>
        @endif

        <form action="{{ route('categories.store') }}" method="POST" class="forum-card space-y-6">
            @csrf

            <div>
                <label for="name" class="forum-label">Category name</label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    value="{{ old('name') }}"
                    class="forum-input @error('name') border-[var(--color-danger)] @enderror"
                    placeholder="e.g., Technology, Gaming, General discussion"
                    required
                >
                @error('name')
                    <p class="forum-status-error mt-2">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-sm text-[var(--color-muted)]">3 to 100 characters and unique across categories.</p>
            </div>

            <div>
                <label for="description" class="forum-label">Description</label>
                <textarea
                    name="description"
                    id="description"
                    rows="5"
                    class="forum-textarea @error('description') border-[var(--color-danger)] @enderror"
                    placeholder="Briefly describe what this category is about."
                >{{ old('description') }}</textarea>
                @error('description')
                    <p class="forum-status-error mt-2">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-sm text-[var(--color-muted)]">Optional. Keep it short and specific.</p>
            </div>

            <div class="forum-divider pt-4">
                <div class="flex flex-wrap items-center justify-end gap-3">
                    <a href="{{ route('categories.index') }}" class="forum-btn-secondary">Cancel</a>
                    <button type="submit" class="forum-btn">Create category</button>
                </div>
            </div>
        </form>
    </section>
@endsection
