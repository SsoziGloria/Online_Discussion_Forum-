<div class="mb-6 pb-6 border-b last:border-b-0" style="margin-left: {{ $depth * 1.75 }}rem;">
    <div class="flex justify-between items-start">
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

    @if($post->children->count())
        <details class="mt-3">
            <summary class="text-sm text-emerald-600 hover:underline cursor-pointer">
                Show replies ({{ $post->children->count() }})
            </summary>
            <div class="mt-4 space-y-6">
                @foreach($post->children as $child)
                    @include('threads.partials.post', ['post' => $child, 'thread' => $thread, 'depth' => $depth + 1])
                @endforeach
            </div>
        </details>
    @endif

    @auth
        <details class="mt-3">
            <summary class="text-sm text-emerald-600 hover:underline cursor-pointer">Reply</summary>
            <div class="mt-3">
                @if($post->parent)
                    <div class="text-xs text-gray-400 mb-2">in reply to {{ $post->parent->user->name }}</div>
                @endif
                <form method="POST" action="{{ route('posts.store', $thread) }}">
                    @csrf
                    <textarea name="body" rows="3"
                              class="w-full border border-gray-300 rounded-2xl p-3 focus:ring-2 focus:ring-emerald-500"
                              placeholder="Write a reply to {{ $post->user->name }}..." required>{{ old('body') }}</textarea>
                    <input type="hidden" name="parent_id" value="{{ $post->id }}">
                    <div class="mt-2">
                        <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded-md text-sm">Reply</button>
                    </div>
                </form>
            </div>
        </details>
    @endauth
</div>
