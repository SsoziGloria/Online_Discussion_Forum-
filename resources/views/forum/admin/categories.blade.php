@extends('layouts.app')

@section('title', 'Category Management · DevDen')

@section('content')
    <section class="mb-8 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div>
            <p class="forum-eyebrow">Admin preview</p>
            <h1 class="forum-title mt-2">Category management</h1>
            <p class="forum-copy mt-4">The management surface reads directly from the `categories` table, and authenticated users may now create new categories.</p>
        </div>
        @auth
            <a href="{{ route('categories.create') }}" class="forum-btn">Create new category</a>
        @else
            <a href="{{ route('login') }}" class="forum-btn">Login to create category</a>
        @endauth
    </section>

    <section class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_22rem]">
        <div class="forum-card overflow-hidden p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[var(--color-border)] text-left">
                    <thead class="bg-[var(--color-surface-soft)] text-sm font-semibold text-[var(--color-muted)]">
                        <tr>
                            <th class="px-6 py-4">Name</th>
                            <th class="px-6 py-4">Description</th>
                            <th class="px-6 py-4">Threads</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--color-border)] bg-white">
                        @forelse ($categories as $category)
                            <tr>
                                <td class="px-6 py-5">
                                    <a href="{{ route('admin.categories', ['category' => $category->id]) }}" class="font-semibold text-[var(--color-text)] transition hover:text-[var(--color-primary)]">
                                        {{ $category->name }}
                                    </a>
                                    <p class="mt-1 text-sm text-[var(--color-muted)]">/{{ $category->slug }}</p>
                                </td>
                                <td class="px-6 py-5 text-sm text-[var(--color-muted)]">{{ $category->description ?: 'No description added yet.' }}</td>
                                <td class="px-6 py-5 forum-data text-sm">{{ $category->threads_count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-10 text-center text-[var(--color-muted)]">No categories exist yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <aside class="forum-card">
            <p class="forum-eyebrow">Category details</p>
            <h2 class="forum-section-title mt-2">{{ $selectedCategory?->name ?? 'No category selected' }}</h2>

            @if ($selectedCategory)
                <form method="POST" action="{{ route('categories.update', $selectedCategory) }}" class="mt-6 space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[var(--color-muted)]">Name</label>
                        <input name="name" value="{{ old('name', $selectedCategory->name) }}" required
                               class="forum-input" />
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[var(--color-muted)]">Slug</label>
                        <input class="forum-input forum-data" disabled value="{{ $selectedCategory->slug }}">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[var(--color-muted)]">Description</label>
                        <textarea name="description" rows="5" class="forum-textarea min-h-32">{{ old('description', $selectedCategory->description) }}</textarea>
                    </div>

                    <div class="flex items-center justify-between text-sm text-[var(--color-muted)]">
                        <span>Current threads</span>
                        <span class="forum-data">{{ $selectedCategory->threads_count }}</span>
                    </div>

                    <div class="forum-divider pt-4">
                        <button type="submit" class="forum-btn w-full">Save category</button>
                    </div>
                </form>
            @else
                <p class="mt-4 text-[var(--color-muted)]">Select a category from the list to preview its management form.</p>
            @endif
        </aside>
    </section>

    <div class="mt-8">
        {{ $categories->links() }}
    </div>
@endsection
