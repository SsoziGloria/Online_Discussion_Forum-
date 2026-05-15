@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-emerald-50 py-10">
    <div class="max-w-4xl mx-auto px-6">
        
        <a href="{{ route('threads.index') }}" class="inline-flex items-center text-emerald-600 hover:underline mb-6">
            ← Back to Discussions
        </a>

        <!-- Thread Content -->
        <div class="bg-white rounded-3xl shadow-xl p-10 mb-8">
            <h1 class="text-3xl font-bold text-gray-800">{{ $thread->title }}</h1>
            <p class="mt-4 text-gray-500">
                By <strong>{{ $thread->user->name }}</strong> • {{ $thread->created_at->diffForHumans() }}
            </p>
            <div class="mt-8 text-gray-700 leading-relaxed border-t pt-8">
                {{ $thread->body }}
            </div>
        </div>

        <!-- Replies -->
        <div class="bg-white rounded-3xl shadow-xl p-10">
            <h2 class="text-2xl font-semibold mb-8">Replies ({{ $thread->posts->count() }})</h2>

            @foreach($thread->posts->whereNull('parent_id') as $post)
                @include('threads.partials.post', ['post' => $post, 'thread' => $thread, 'depth' => 0])
            @endforeach

            <!-- Reply Form -->
            @auth
            <div class="mt-12">
                <h3 class="font-semibold mb-4 text-lg">Write a Reply</h3>
                <form method="POST" action="{{ route('posts.store', $thread) }}">
                    @csrf
                    <textarea name="body" rows="5" 
                              class="w-full border border-gray-300 rounded-2xl p-5 focus:ring-2 focus:ring-emerald-500"
                              placeholder="Write your reply here..." required>{{ old('body') }}</textarea>
                    
                    <button type="submit" 
                            class="mt-5 bg-gradient-to-r from-emerald-600 to-blue-600 text-white px-10 py-3.5 rounded-2xl font-medium hover:brightness-110">
                        Post Reply
                    </button>
                </form>
            </div>
            @endauth
        </div>
    </div>
</div>
@endsection

