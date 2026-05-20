@extends('layouts.app')

@section('title', 'Edit Thread · DevDen')

@section('content')
    <section class="mx-auto max-w-3xl">
        <div class="mb-8">
            <p class="forum-eyebrow">Edit thread</p>
            <h1 class="forum-title mt-2">Update your discussion</h1>
            <p class="forum-copy mt-4">Changes here update both the thread record and the stored opening post.</p>
        </div>

        @if ($errors->any())
            <div class="forum-banner-danger mb-6">
                Please fix the highlighted fields and try again.
            </div>
        @endif

        <form method="POST" action="{{ route('threads.update', $thread) }}" class="forum-card space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="category_id" class="forum-label">Category</label>
                <select id="category_id" name="category_id" class="forum-select" required>
                    @foreach ($categories as $category)
                        <option
                            value="{{ $category->id }}"
                            @selected((string) old('category_id', $thread->category_id) === (string) $category->id)
                            @disabled($category->is_locked && (int) $category->id !== (int) $thread->category_id)
                        >
                            {{ $category->name }}{{ $category->is_locked && (int) $category->id !== (int) $thread->category_id ? ' (Locked)' : '' }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
            </div>

            <div>
                <label for="title" class="forum-label">Thread title</label>
                <input id="title" name="title" class="forum-input" type="text" value="{{ old('title', $thread->title) }}" required>
                <x-input-error :messages="$errors->get('title')" class="mt-2" />
            </div>

            <div>
                <label for="body" class="forum-label">Opening post</label>
                <textarea id="body" name="body" rows="12" class="forum-textarea font-mono text-sm" required>{{ old('body', $thread->body) }}</textarea>
                <x-input-error :messages="$errors->get('body')" class="mt-2" />
            </div>

            <div class="forum-divider pt-4">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <a href="{{ route('threads.show', $thread) }}" class="forum-btn-secondary">Cancel</a>
                    <button type="submit" class="forum-btn">Save changes</button>
                </div>
            </div>
        </form>
    </section>
@endsection
