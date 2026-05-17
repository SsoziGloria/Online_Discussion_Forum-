@extends('layouts.app')

@section('title', $category->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Category Header -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 mb-6 text-white">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold mb-2">{{ $category->name }}</h1>
                <p class="text-blue-100">{{ $category->description ?? 'Welcome to this discussion category!' }}</p>
                <div class="mt-3 text-sm text-blue-100">
                    📊 {{ $category->thread_count }} {{ Str::plural('thread', $category->thread_count) }}
                </div>
            </div>
            
            <div class="flex space-x-3">
                @auth
                    <a href="{{ route('threads.create', $category) }}" 
                       class="bg-white text-blue-600 hover:bg-blue-50 font-semibold py-2 px-6 rounded-lg transition duration-200 shadow-md">
                        + New Thread
                    </a>
                @else
                    <a href="{{ route('login') }}" 
                       class="bg-white text-blue-600 hover:bg-blue-50 font-semibold py-2 px-6 rounded-lg transition duration-200 shadow-md">
                        Login to Post
                    </a>
                @endauth

                @can('update', $category)
                    <a href="{{ route('categories.edit', $category) }}" 
                       class="bg-gray-700 hover:bg-gray-800 text-white font-semibold py-2 px-4 rounded-lg transition">
                        Edit Category
                    </a>
                @endcan
            </div>
        </div>
    </div>

    <!-- Sort Options -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <div class="flex items-center justify-between">
            <div class="text-gray-600">
                Showing {{ $threads->firstItem() ?? 0 }} to {{ $threads->lastItem() ?? 0 }} of {{ $threads->total() }} threads
            </div>
            
            <div class="flex items-center space-x-3">
                <label class="text-gray-600">Sort by:</label>
                <select id="sort-select" class="border rounded-lg px-3 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest Activity</option>
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                    <option value="most_replies" {{ request('sort') == 'most_replies' ? 'selected' : '' }}>Most Replies</option>
                    <option value="most_votes" {{ request('sort') == 'most_votes' ? 'selected' : '' }}>Most Votes</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Threads List -->
    <div class="space-y-4">
        @forelse($threads as $thread)
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-2">
                                @if($thread->is_pinned)
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2 py-1 rounded">
                                        📌 Pinned
                                    </span>
                                @endif
                                @if($thread->is_locked)
                                    <span class="bg-red-100 text-red-800 text-xs font-semibold px-2 py-1 rounded">
                                        🔒 Locked
                                    </span>
                                @endif
                            </div>
                            
                            <h2 class="text-xl font-semibold mb-2">
                                <a href="{{ route('threads.show', $thread) }}" 
                                   class="text-gray-800 hover:text-blue-600 transition">
                                    {{ $thread->title }}
                                </a>
                            </h2>
                            
                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                <span>
                                    👤 By {{ $thread->user ? $thread->user->username : 'Deleted User' }}
                                </span>
                                <span>
                                    💬 {{ $thread->replies_count ?? 0 }} replies
                                </span>
                                <span>
                                    ⭐ {{ $thread->vote_score ?? 0 }} votes
                                </span>
                                <span>
                                    🕒 {{ $thread->last_activity_at ? $thread->last_activity_at->diffForHumans() : $thread->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                        
                        @can('moderate', $thread)
                            <div class="flex space-x-2 ml-4">
                                <a href="{{ route('threads.edit', $thread) }}" 
                                   class="text-gray-500 hover:text-blue-600 text-sm">
                                    Edit
                                </a>
                            </div>
                        @endcan
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <p class="text-gray-500 text-lg mb-4">No threads in this category yet.</p>
                @auth
                    <a href="{{ route('threads.create', $category) }}" 
                       class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition">
                        Be the first to create a thread!
                    </a>
                @else
                    <p class="text-gray-600">
                        <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login</a> 
                        to start the discussion.
                    </p>
                @endauth
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $threads->appends(request()->query())->links() }}
    </div>
</div>

<script>
document.getElementById('sort-select')?.addEventListener('change', function() {
    const url = new URL(window.location.href);
    url.searchParams.set('sort', this.value);
    window.location.href = url.toString();
});
</script>
@endsection
