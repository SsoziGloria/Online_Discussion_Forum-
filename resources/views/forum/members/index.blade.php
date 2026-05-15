@extends('layouts.app')

@section('title', 'Members · DevDen')

@section('content')
    <section class="mb-8 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div>
            <p class="forum-eyebrow">Members</p>
            <h1 class="forum-title mt-2">Member directory</h1>
            <p class="forum-copy mt-4">Browse community members, review their activity, and visit public profiles.</p>
        </div>
        <form action="{{ route('members.index') }}" method="GET" class="w-full max-w-sm">
            <input type="search" name="q" value="{{ $query }}" class="forum-input" placeholder="Search members">
        </form>
    </section>

    <section class="forum-card overflow-hidden p-0">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[var(--color-border)] text-left">
                <thead class="bg-[var(--color-surface-soft)] text-sm font-semibold text-[var(--color-muted)]">
                    <tr>
                        <th class="px-6 py-4">Member</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4">Reputation</th>
                        <th class="px-6 py-4">Activity</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--color-border)] bg-white">
                    @forelse ($members as $member)
                        <tr class="align-top">
                            <td class="px-6 py-5">
                                <a href="{{ route('members.show', $member->username) }}" class="font-semibold text-[var(--color-text)] transition hover:text-[var(--color-primary)]">
                                    {{ $member->display_name ?? $member->username }}
                                </a>
                                <p class="mt-1 text-sm text-[var(--color-muted)]">{{ '@'.$member->username }} · {{ $member->email }}</p>
                            </td>
                            <td class="px-6 py-5">
                                <span class="forum-tag-neutral">{{ ucfirst($member->role) }}</span>
                            </td>
                            <td class="px-6 py-5 forum-data text-sm">{{ number_format($member->reputation) }}</td>
                            <td class="px-6 py-5 text-sm text-[var(--color-muted)]">
                                {{ $member->threads_count }} threads · {{ $member->posts_count }} replies · {{ $member->warnings_count }} warnings
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-[var(--color-muted)]">No members matched this query.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div class="mt-8">
        {{ $members->links() }}
    </div>
@endsection
