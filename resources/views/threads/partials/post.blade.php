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

    <div class="mt-4 flex items-center gap-3 text-sm text-gray-600">
        <span class="font-semibold text-gray-800">Score:</span>
        <span class="font-medium">{{ $post->vote_score }}</span>

        @auth
            @if($post->user_id !== auth()->id())
                @php
                    $userVote = $post->votes->firstWhere('user_id', auth()->id());
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
