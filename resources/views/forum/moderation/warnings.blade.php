
@extends('layouts.app')

@section('title', 'Warnings · DevDen')

@php
use Illuminate\Support\Str;
@endphp

@section('content')
    <section class="mb-8">
        <div class="mb-6">
            <p class="forum-eyebrow">Moderator tools</p>
            <h1 class="forum-title mt-2">Issue a warning</h1>
        </div>

        <div class="forum-card">
            <form action="{{ route('warnings.store') }}" method="POST">
                @csrf
                <div class="grid gap-4 md:grid-cols-3">
                    <div>
                        <label class="block text-sm font-medium text-[var(--color-muted)]">Member</label>
                        <select name="user_id" required class="mt-1 block w-full rounded border border-[var(--color-border)] bg-[var(--color-surface)] px-3 py-2 text-sm text-[var(--color-text)]">
                            <option value="">Select a member</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->display_name ?? $user->username }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-[var(--color-muted)]">Reason</label>
                        <textarea name="reason" rows="3" required class="mt-1 block w-full rounded border border-[var(--color-border)] bg-[var(--color-surface)] px-3 py-2 text-sm text-[var(--color-text)]" placeholder="Explain why this warning is being issued..."></textarea>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="forum-btn">Issue warning</button>
                </div>
            </form>
        </div>
    </section>

    <section>
        <div class="mb-6">
            <p class="forum-eyebrow">Existing warnings</p>
            <h2 class="forum-title mt-2">Recent warnings</h2>
        </div>

        <div class="forum-card overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="text-[var(--color-muted)]">
                        <th class="px-4 py-2">Member</th>
                        <th class="px-4 py-2">Issued by</th>
                        <th class="px-4 py-2">Reason</th>
                        <th class="px-4 py-2">When</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($warnings as $warning)
                        <tr class="border-t border-[var(--color-border)]">
                            <td class="px-4 py-3">{{ $warning->user?->display_name ?? $warning->user?->username ?? '—' }}</td>
                            <td class="px-4 py-3">{{ $warning->issuer?->display_name ?? $warning->issuer?->username ?? '—' }}</td>
                            <td class="px-4 py-3">{{ Str::limit($warning->reason, 120) }}</td>
                            <td class="px-4 py-3 text-[var(--color-muted)]">{{ $warning->created_at->diffForHumans() }}</td>
                            <td class="px-4 py-3">
                                <form action="{{ route('warnings.destroy', $warning->id) }}" method="POST" onsubmit="return confirm('Revoke this warning?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm font-semibold text-[var(--color-primary)]">Revoke</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-[var(--color-muted)]">No warnings have been issued yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $warnings->links() }}
            </div>
        </div>
    </section>
@endsection
