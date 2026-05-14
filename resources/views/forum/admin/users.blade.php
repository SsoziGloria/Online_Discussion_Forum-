@extends('layouts.app')

@section('title', 'User Management · DevDen')

@section('content')
    <section class="mb-8 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div>
            <p class="forum-eyebrow">Admin preview</p>
            <h1 class="forum-title mt-2">User management</h1>
            <p class="forum-copy mt-4">The table is backed by the `users` model, with thread, reply, and warning counts surfaced for moderation context.</p>
        </div>
        <form action="{{ route('admin.users') }}" method="GET" class="w-full max-w-sm">
            <input type="search" name="q" value="{{ $query }}" class="forum-input" placeholder="Search users">
        </form>
    </section>

    <section class="forum-card overflow-hidden p-0">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[var(--color-border)] text-left">
                <thead class="bg-[var(--color-surface-soft)] text-sm font-semibold text-[var(--color-muted)]">
                    <tr>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4">Reputation</th>
                        <th class="px-6 py-4">Activity</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--color-border)] bg-white">
                    @forelse ($users as $user)
                        <tr class="align-top">
                            <td class="px-6 py-5">
                                <a href="{{ route('members.show', $user->username) }}" class="font-semibold text-[var(--color-text)] transition hover:text-[var(--color-primary)]">
                                    {{ $user->display_name ?? $user->username }}
                                </a>
                                <p class="mt-1 text-sm text-[var(--color-muted)]">{{ '@'.$user->username }} · {{ $user->email }}</p>
                            </td>
                            <td class="px-6 py-5">
                                <span class="forum-tag-neutral">{{ ucfirst($user->role) }}</span>
                            </td>
                            <td class="px-6 py-5 forum-data text-sm">{{ number_format($user->reputation) }}</td>
                            <td class="px-6 py-5 text-sm text-[var(--color-muted)]">
                                {{ $user->threads_count }} threads · {{ $user->posts_count }} replies · {{ $user->warnings_count }} warnings
                            </td>
                            <td class="px-6 py-5">
                                <span class="{{ $user->is_banned ? 'text-[var(--color-danger)]' : 'text-[var(--color-success)]' }} text-sm font-semibold">
                                    {{ $user->is_banned ? 'Banned' : 'Active' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-[var(--color-muted)]">No users matched this query.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div class="mt-8">
        {{ $users->links() }}
    </div>
@endsection
