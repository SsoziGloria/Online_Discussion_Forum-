@extends('layouts.app')

@section('title', '403 · DevDen')

@section('content')
    <section class="flex min-h-[70vh] items-center justify-center px-4 py-10">
        <div class="forum-card mx-auto w-full max-w-2xl text-center">
            <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full" style="background: rgba(255, 218, 214, 0.7); color: var(--color-danger);">
                <span class="text-5xl font-bold">!</span>
            </div>

            <p class="forum-eyebrow mt-8">Access control</p>
            <h1 class="mt-3 forum-title text-5xl md:text-6xl">Access denied</h1>
            <p class="forum-copy mx-auto mt-5 max-w-xl">
                You do not have permission to perform this action in the current account context. If this should be available to you, sign in with the correct account or return to a public page.
            </p>

            <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('home') }}" class="forum-btn">Return home</a>
                @guest
                    <a href="{{ route('login') }}" class="forum-btn-secondary">Log in</a>
                @endguest
            </div>
        </div>
    </section>
@endsection
