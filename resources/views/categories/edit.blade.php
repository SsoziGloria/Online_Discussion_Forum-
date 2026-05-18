@extends('layouts.app')

@section('title', 'Edit category · DevDen')

@section('content')
    <section class="mx-auto max-w-3xl">
        <div class="mb-8">
            <p class="forum-eyebrow">Category settings</p>
            <h1 class="forum-title mt-2">Edit {{ $category->name }}</h1>
            <p class="forum-copy mt-4">Update the category details to keep labels and descriptions clear for the community.</p>
        </div>

        @if ($errors->any())
            <div class="forum-banner-danger mb-6">Please fix the highlighted fields and try again.</div>
        @endif

        <form action="{{ route('categories.update', $category) }}" method="POST" class="forum-card space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="forum-label">Category name</label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    value="{{ old('name', $category->name) }}"
                    class="forum-input @error('name') border-[var(--color-danger)] @enderror"
                    required
                >
                @error('name')
                    <p class="forum-status-error mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="forum-label">Description</label>
                <textarea
                    name="description"
                    id="description"
                    rows="5"
                    class="forum-textarea @error('description') border-[var(--color-danger)] @enderror"
                >{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <p class="forum-status-error mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="forum-divider pt-4">
                <div class="flex flex-wrap items-center justify-end gap-3">
                    <a href="{{ route('categories.show', $category) }}" class="forum-btn-secondary">Cancel</a>
                    <button type="submit" class="forum-btn">Save category</button>
                </div>
            </div>
        </form>
    </section>
@endsection
