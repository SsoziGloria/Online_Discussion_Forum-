<div class="mb-8 pb-8 border-b last:border-b-0">
    <div class="flex justify-between items-start gap-4">
        <div>
            <strong class="text-gray-800">{{ $post->user->name }}</strong>
            <span class="text-xs text-gray-400 ml-3">{{ $post->created_at->diffForHumans() }}</span>
        </div>

        @if(auth()->check() && $post->user_id === auth()->id())
            <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Delete this reply?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-700 text-sm">Delete</button>
            </form>
        @endif
    </div>

    <p class="mt-3 text-gray-700">{{ $post->body }}</p>

    <div class="mt-4 flex flex-col gap-3 text-sm text-gray-600">
        <div class="flex flex-wrap items-center gap-3">
            <span class="font-semibold text-gray-800">Score:</span>
            <span class="font-medium">{{ $post->vote_score }}</span>

            @auth
                @if($post->user_id !== auth()->id())
                    @php
                        $userVote = $post->votes->firstWhere('user_id', auth()->id());
                        $hasFlagged = $post->flags->where('reported_by', auth()->id())->isNotEmpty();
                    @endphp

                    <form method="POST" action="{{ route('posts.vote', $post) }}" class="inline-flex items-center gap-2">
                        @csrf
                        <input type="hidden" name="value" value="1">
                        <button type="submit"
                            class="rounded-full px-3 py-1 text-xs font-semibold transition 
                                {{ $userVote?->value === 1 ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                            Upvote
                        </button>
                    </form>

                    <form method="POST" action="{{ route('posts.vote', $post) }}" class="inline-flex items-center gap-2">
                        @csrf
                        <input type="hidden" name="value" value="-1">
                        <button type="submit"
                            class="rounded-full px-3 py-1 text-xs font-semibold transition 
                                {{ $userVote?->value === -1 ? 'bg-red-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                            Downvote
                        </button>
                    </form>

                    @unless($hasFlagged)
                        <button
                            type="button"
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'flag-post-{{ $post->id }}')"
                            class="rounded-full px-3 py-1 text-xs font-semibold bg-yellow-100 text-amber-900 hover:bg-yellow-200 transition"
                        >
                            Flag
                        </button>
                    @endunless
                @endif
            @endauth
        </div>

        @auth
            @if($post->user_id !== auth()->id())
                @if($hasFlagged)
                    <div class="rounded-3xl border border-orange-200 bg-orange-50 px-4 py-3 text-sm text-orange-800">
                        You have already reported this post.
                    </div>
                @else
                    <x-modal name="flag-post-{{ $post->id }}" :show="$errors->has('reason') && old('reported_post_id') == $post->id" focusable>
                        <div class="p-6">
                            <div class="mb-4">
                                <h2 class="text-xl font-semibold text-gray-900">Report this post</h2>
                                <p class="mt-2 text-sm text-gray-600">Select the reason that best matches the issue. Our moderators will review the report.</p>
                            </div>

                            <form method="POST" action="{{ route('posts.report.store', $post) }}" class="space-y-6">
                                @csrf
                                <input type="hidden" name="reported_post_id" value="{{ $post->id }}">

                                <fieldset class="space-y-3">
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
                                                <span class="block text-sm font-semibold capitalize text-[var(--color-text)]">{{ $reason }}</span>
                                                <span class="mt-1 block text-xs leading-5 text-[var(--color-muted)]">{{ $description }}</span>
                                            </span>
                                        </label>
                                    @endforeach
                                </fieldset>

                                @error('reason')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror

                                <div class="flex flex-wrap items-center justify-end gap-3 pt-4">
                                    <button type="button" x-data="" x-on:click.prevent="$dispatch('close-modal', 'flag-post-{{ $post->id }}')" class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</button>
                                    <button type="submit" class="rounded-full bg-red-600 px-5 py-2 text-sm font-semibold text-white hover:bg-red-700">Submit flag</button>
                                </div>
                            </form>
                        </div>
                    </x-modal>
                @endif
            @endif
        @endauth
    </div>

    @if($post->children->isNotEmpty())
        <details class="mt-4 rounded-3xl border border-gray-200 bg-slate-50 p-4">
            <summary class="cursor-pointer text-sm font-semibold text-blue-600 hover:text-blue-700">
                Show replies ({{ $post->children->count() }})
            </summary>

            <div class="mt-6 space-y-6 border-l-2 border-gray-200 pl-6">
                @foreach($post->children as $child)
                    @include('threads.partials.post', ['post' => $child, 'thread' => $thread])
                @endforeach
            </div>
        </details>
    @endif

    @auth
        <details class="mt-4 rounded-3xl border border-gray-200 bg-slate-50 p-4" @if(old('parent_id') == $post->id) open @endif>
            <summary class="cursor-pointer text-sm font-semibold text-blue-600 hover:text-blue-700">Reply to this post</summary>
            <form method="POST" action="{{ route('posts.store', $thread) }}" class="mt-4 space-y-4">
                @csrf
                <input type="hidden" name="parent_id" value="{{ $post->id }}">
                <textarea name="body" rows="3"
                          class="w-full border border-gray-300 rounded-2xl p-4 focus:ring-2 focus:ring-emerald-500"
                          placeholder="Write your reply here..." required>{{ old('parent_id') == $post->id ? old('body') : '' }}</textarea>
                @error('body')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
                <button type="submit"
                        class="bg-gradient-to-r from-emerald-600 to-blue-600 text-white px-6 py-2.5 rounded-2xl font-medium hover:brightness-110">
                    Post Reply
                </button>
            </form>
        </details>
    @endauth
</div>
