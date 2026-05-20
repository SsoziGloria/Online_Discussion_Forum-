@extends('layouts.app')

@section('title', 'Profile Settings · DevDen')

@section('content')
    <section class="mx-auto max-w-3xl">
        <div class="mb-8">
            <p class="forum-eyebrow">Account settings</p>
            <h1 class="forum-title mt-2">Profile settings</h1>
            <p class="forum-copy mt-4">Update the public details connected to your account.</p>
        </div>

        <form method="POST" action="{{ route('settings.profile.update') }}" class="space-y-6">
            @csrf
            @method('PATCH')

            <section class="forum-card">
                <h2 class="forum-section-title">Public information</h2>
                <div class="mt-6 space-y-5">
                    <div>
                        <label for="name" class="mb-2 block text-sm font-semibold text-[var(--color-muted)]">Display name</label>
                        <input id="name" name="name" class="forum-input" value="{{ old('name', $member?->name) }}" required autocomplete="name">
                        @error('name')
                            <p class="mt-2 text-sm text-[var(--color-danger)]">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-[var(--color-muted)]">Email</label>
                        <input id="email" name="email" type="email" class="forum-input" value="{{ old('email', $member?->email) }}" required autocomplete="email">
                        @error('email')
                            <p class="mt-2 text-sm text-[var(--color-danger)]">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="bio" class="mb-2 block text-sm font-semibold text-[var(--color-muted)]">Bio</label>
                        <textarea id="bio" name="bio" class="forum-textarea min-h-32" rows="6">{{ old('bio', $member?->bio) }}</textarea>
                        @error('bio')
                            <p class="mt-2 text-sm text-[var(--color-danger)]">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <div class="forum-divider pt-4">
                <div class="flex items-center justify-between gap-4">
                    @if (session('status') === 'profile-updated')
                        <p class="text-sm text-[var(--color-success)]">Saved.</p>
                    @else
                        <p class="text-sm text-[var(--color-muted)]">Changes will update your public profile immediately.</p>
                    @endif
                    <button type="submit" class="forum-btn">Save changes</button>
                </div>
            </div>
        </form>
    </section>
@endsection
