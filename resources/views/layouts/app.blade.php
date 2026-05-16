<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'DevDen')</title>
    <meta name="description" content="@yield('meta_description', 'A welcoming discussion forum for categories, conversations, replies, and community activity.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&family=Source+Sans+3:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:FILL@0..1" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="forum-body">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:rounded-lg focus:bg-[var(--color-primary)] focus:px-4 focus:py-2 focus:text-[var(--color-on-primary)]">
        Skip to content
    </a>

    @include('layouts.navigation')

    <main id="main-content" class="forum-shell">
        @if (session('success'))
            <div class="forum-banner-success mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="forum-banner-error mb-6">
                {{ session('error') }}
            </div>
        @endif

        @if (session('info'))
            <div class="forum-banner-info mb-6">
                {{ session('info') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="border-t border-[var(--color-border)] bg-[var(--color-surface-soft)]">
        <div class="mx-auto flex max-w-6xl flex-col gap-4 px-4 py-8 text-sm text-[var(--color-muted)] md:flex-row md:items-center md:justify-between md:px-6">
            <div>
                <span class="font-semibold tracking-tight text-[var(--color-primary)]">DevDen</span>
                <p class="mt-1">A welcoming place for thoughtful conversations, shared questions, and community updates.</p>
            </div>
            <nav class="flex flex-wrap gap-4">
                <a href="{{ route('home') }}" class="transition hover:text-[var(--color-primary)]">Discussions</a>
                <a href="{{ route('categories.index') }}" class="transition hover:text-[var(--color-primary)]">Categories</a>
                <a href="{{ route('admin.users') }}" class="transition hover:text-[var(--color-primary)]">Members</a>
                <a href="{{ route('moderation.flags') }}" class="transition hover:text-[var(--color-primary)]">Moderation</a>
            </nav>
        </div>
    </footer>
</body>
</html>
