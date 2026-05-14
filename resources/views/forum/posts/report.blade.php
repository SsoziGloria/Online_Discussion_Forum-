@extends('layouts.app')

@section('title', 'Report Reply · DevDen')

@section('content')
    <section class="mx-auto w-full max-w-3xl">
        <div class="forum-card overflow-hidden p-0">
            <div class="border-b px-6 py-5 md:px-8" style="border-color: var(--color-border); background: rgba(248, 241, 223, 0.82);">
                <p class="forum-eyebrow">Moderation request</p>
                <h1 class="mt-2 forum-section-title">Report this reply for moderator review.</h1>
                <p class="mt-3 text-sm leading-6 text-[var(--color-muted)]">
                    Select the reason that best matches the issue. The report will be linked to the reply inside
                    <a href="{{ route('threads.show', $post->thread->slug) }}" class="font-semibold text-[var(--color-primary)] transition hover:text-[var(--color-secondary)]">
                        {{ $post->thread->title }}
                    </a>.
                </p>
            </div>

            <div class="p-6 md:p-8">
                <div class="forum-card-muted p-4">
                    <p class="forum-eyebrow">Reply excerpt</p>
                    <p class="mt-3 text-base leading-7 text-[var(--color-text)]">
                        {{ \Illuminate\Support\Str::limit($post->body, 280) }}
                    </p>
                    <p class="mt-3 text-sm text-[var(--color-muted)]">
                        Posted by {{ $post->user?->display_name ?? $post->user?->username ?? 'Unknown member' }}
                    </p>
                </div>

                <form method="POST" action="{{ route('posts.report.store', $post) }}" class="mt-8 space-y-6">
                    @csrf

                    <fieldset>
                        <legend class="forum-label">Why are you reporting this reply?</legend>
                        <div class="space-y-3">
                            @foreach ([
                                'spam' => 'Unsolicited promotion, repetitive content, or irrelevant links.',
                                'harassment' => 'Personal attacks, targeted abuse, or hostile language.',
                                'misinformation' => 'Misleading or dangerously incorrect technical claims.',
                                'inappropriate' => 'Content that is offensive, explicit, or unsuitable for the forum.',
                                'other' => 'Anything else that should be reviewed under community guidelines.',
                            ] as $reason => $description)
                                <label class="flex cursor-pointer items-start gap-3 rounded-2xl border px-4 py-4 transition hover:bg-[rgba(248,241,223,0.72)]" style="border-color: var(--color-border);">
                                    <input
                                        type="radio"
                                        name="reason"
                                        value="{{ $reason }}"
                                        class="mt-1 border-[var(--color-border)] text-[var(--color-primary)] focus:ring-[rgba(111,51,29,0.18)]"
                                        @checked(old('reason') === $reason)
                                        required
                                    >
                                    <span>
                                        <span class="block text-base font-semibold capitalize text-[var(--color-text)]">{{ $reason }}</span>
                                        <span class="mt-1 block text-sm leading-6 text-[var(--color-muted)]">{{ $description }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('reason')" class="mt-2" />
                    </fieldset>

                    <div class="forum-divider pt-4">
                        <div class="flex flex-wrap items-center justify-end gap-3">
                            <a href="{{ route('threads.show', $post->thread->slug) }}" class="forum-btn-secondary">Cancel</a>
                            <button type="submit" class="forum-btn">Submit report</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
