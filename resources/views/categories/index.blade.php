@extends('layouts.app')

@section('title', 'Forum categories · DevDen')

@section('content')
    <section class="mb-8 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div>
            <p class="forum-eyebrow">Category index</p>
            <h1 class="forum-title mt-2">Discussion categories</h1>
            <p class="forum-copy mt-4">Browse every category, check recent activity, and jump into the threads that matter to you.</p>
        </div>
        @can('create', App\Models\Category::class)
            <a href="{{ route('categories.create') }}" class="forum-btn">Create new category</a>
        @endcan
    </section>

    <section class="space-y-4">
        @forelse($categories as $category)
            <article class="forum-card">
                <div class="flex flex-col gap-5 md:flex-row md:items-start md:justify-between">
                    <div class="space-y-3">
                        <a href="{{ route('categories.show', $category) }}" class="text-2xl font-semibold tracking-[-0.03em] text-[var(--color-text)] transition hover:text-[var(--color-primary)]">
                            {{ $category->name }}
                        </a>
                        <p class="max-w-[72ch] text-base leading-7 text-[var(--color-muted)]">{{ $category->description ?? 'No description available yet.' }}</p>

                        <div class="flex flex-wrap items-center gap-3 text-sm text-[var(--color-muted)]">
                            <span class="forum-data">{{ $category->thread_count }}</span>
                            <span>{{ Str::plural('thread', $category->thread_count) }}</span>
                            @if($latestActivity = $category->latest_activity)
                                <span>·</span>
                                <span>Latest</span>
                                <a href="{{ route('threads.show', $latestActivity['thread']) }}" class="font-semibold text-[var(--color-primary)] transition hover:opacity-80">
                                    {{ Str::limit($latestActivity['thread']->title, 40) }}
                                </a>
                                <span>· {{ $latestActivity['time']->diffForHumans() }}</span>
                            @endif
                        </div>
                    </div>

                    @can('update', $category)
                        <div class="flex shrink-0 items-center gap-2">
                            <a href="{{ route('categories.edit', $category) }}" class="forum-action-link">Edit</a>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="forum-action-link-danger">Delete</button>
                            </form>
                        </div>
                    @endcan
                </div>
            </article>
        @empty
            <div class="forum-card text-center">
                <p class="forum-section-title">No categories found</p>
                @can('create', App\Models\Category::class)
                    <a href="{{ route('categories.create') }}" class="forum-link mt-3 inline-flex items-center">Create the first category</a>
                @endcan
            </div>
        @endforelse
    </section>

    <div class="mt-8">
        {{ $categories->links() }}
    </div>
@endsection
