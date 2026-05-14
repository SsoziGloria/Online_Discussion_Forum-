@extends('layouts.app')

@section('title', 'Profile Settings Preview · DevDen')

@section('content')
    <section class="mx-auto max-w-3xl">
        <div class="mb-8">
            <p class="forum-eyebrow">Settings preview</p>
            <h1 class="forum-title mt-2">Profile settings</h1>
            <p class="forum-copy mt-4">This page mirrors the prototype but remains read-only until update controllers are finalized.</p>
        </div>

        <div class="space-y-6">
            <section class="forum-card">
                <h2 class="forum-section-title">Public information</h2>
                <div class="mt-6 space-y-5">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[var(--color-muted)]">Display name</label>
                        <input class="forum-input" disabled value="{{ $member?->display_name }}">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[var(--color-muted)]">Avatar URL</label>
                        <input class="forum-input" disabled value="{{ $member?->avatar_url }}">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[var(--color-muted)]">Bio</label>
                        <textarea class="forum-textarea min-h-32" disabled>{{ $member?->bio }}</textarea>
                    </div>
                </div>
            </section>

            <section class="forum-card">
                <h2 class="forum-section-title">Account security</h2>
                <div class="mt-6 grid gap-5">
                    <input class="forum-input" disabled type="password" value="current-password">
                    <input class="forum-input" disabled type="password" value="new-password">
                    <input class="forum-input" disabled type="password" value="confirm-password">
                </div>
                <div class="forum-divider mt-6 pt-4">
                    <div class="flex items-center justify-between gap-4">
                        <p class="text-sm text-[var(--color-muted)]">Update actions are intentionally unavailable in this pass.</p>
                        <span class="forum-btn-disabled">Save changes</span>
                    </div>
                </div>
            </section>
        </div>
    </section>
@endsection
