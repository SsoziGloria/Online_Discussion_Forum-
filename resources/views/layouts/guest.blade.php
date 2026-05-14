<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="forum-shell-auth">
            <section class="forum-auth-hero">
                <div class="max-w-2xl">
                    <a href="{{ route('home') }}" class="forum-tag-neutral">Modern Discourse</a>
                    <p class="forum-eyebrow mt-8">Member access</p>
                    <h1 class="forum-title mt-3">Your community account, all in one familiar place.</h1>
                    <p class="forum-copy mt-6">
                        Sign in, create an account, or recover access using the same calm and welcoming experience as the rest of the forum.
                    </p>
                </div>

                <div class="mt-10 grid gap-4 sm:grid-cols-3">
                    <div class="forum-card-muted p-4">
                        <p class="forum-eyebrow">Profiles</p>
                        <p class="forum-data mt-2 text-xl text-[var(--color-primary)]">Names first</p>
                    </div>
                    <div class="forum-card-muted p-4">
                        <p class="forum-eyebrow">Recovery</p>
                        <p class="forum-data mt-2 text-xl text-[var(--color-primary)]">Email-based</p>
                    </div>
                    <div class="forum-card-muted p-4">
                        <p class="forum-eyebrow">Security</p>
                        <p class="forum-data mt-2 text-xl text-[var(--color-primary)]">Session aware</p>
                    </div>
                </div>
            </section>

            <main class="forum-auth-panel">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
