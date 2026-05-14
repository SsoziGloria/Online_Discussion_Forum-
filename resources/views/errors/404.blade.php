@extends('layouts.app')

@section('title', '404 · DevDen')

@section('content')
    <section class="flex min-h-[70vh] items-center justify-center px-4 py-10">
        <div class="mx-auto flex max-w-3xl flex-col items-center text-center">
            <div class="relative">
                <p class="select-none text-[7rem] font-extrabold tracking-[-0.08em] text-[color:rgba(219,199,187,0.95)] md:text-[10rem]">404</p>
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="rounded-full border px-5 py-2 text-sm font-semibold uppercase tracking-[0.18em]" style="border-color: var(--color-border); background: rgba(255,255,255,0.72); color: var(--color-primary);">
                        Missing page
                    </div>
                </div>
            </div>

            <h1 class="mt-6 forum-title text-5xl md:text-6xl">Page not found</h1>
            <p class="forum-copy mt-5 max-w-2xl">
                The page, thread, or member profile you requested does not exist at this address. It may have moved, been removed, or never been published into this forum build.
            </p>

            <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('home') }}" class="forum-btn">Return home</a>
                <a href="{{ route('search') }}" class="forum-btn-secondary">Search discussions</a>
            </div>

            <div class="forum-divider mt-10 w-full max-w-md pt-5 text-sm text-[var(--color-muted)]">
                <div class="flex flex-wrap items-center justify-center gap-3">
                    <a href="{{ route('home') }}" class="transition hover:text-[var(--color-primary)]">Discussions</a>
                    <span>•</span>
                    <a href="{{ route('admin.categories') }}" class="transition hover:text-[var(--color-primary)]">Categories</a>
                    <span>•</span>
                    <a href="{{ route('search') }}" class="transition hover:text-[var(--color-primary)]">Search</a>
                </div>
            </div>
        </div>
    </section>
@endsection
