@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-emerald-50">
    <div class="max-w-6xl mx-auto py-12 px-6">
        
        <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-5xl font-bold bg-gradient-to-r from-blue-600 to-emerald-600 bg-clip-text text-transparent">
                    💬 Discussion Forum
                </h1>
                <p class="text-gray-600 mt-2 text-lg">Share ideas • Ask questions • Learn together</p>
            </div>
            
            @auth
            <a href="{{ route('threads.create') }}" 
               class="bg-gradient-to-r from-emerald-600 to-blue-600 hover:from-emerald-700 hover:to-blue-700 text-white px-8 py-4 rounded-2xl font-semibold shadow-lg shadow-emerald-500/40 flex items-center gap-2 text-lg transition-all">
                ✨ New Discussion
            </a>
            @endauth
        </div>

        @if(session('success'))
            <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-6 py-4 rounded-2xl mb-8">
                {{ session('success') }}
            </div>
        @endif

        @if($threads->isEmpty())
            <div class="text-center py-20 bg-white rounded-3xl shadow">
                <p class="text-2xl text-gray-600">No discussions yet. Be the first!</p>
            </div>
        @else
            @foreach($threads as $thread)
            <div class="bg-white rounded-3xl shadow hover:shadow-xl p-8 mb-6 border border-gray-100">
                <h3 class="text-2xl font-semibold">
                    <a href="{{ route('threads.show', $thread) }}" class="hover:text-blue-600">{{ $thread->title }}</a>
                </h3>
                
                <p class="mt-4 text-gray-600">{{ Str::limit($thread->body, 250) }}</p>
                
                <div class="mt-6 flex justify-between items-center">
                    <div class="text-sm text-gray-500">
                        By <strong>{{ $thread->user?->name }}</strong> • {{ $thread->created_at->diffForHumans() }}
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="bg-emerald-100 text-emerald-700 px-4 py-2 rounded-2xl text-sm font-medium">
                            {{ $thread->posts->count() }} replies
                        </span>
                        @if(auth()->check() && $thread->user_id === auth()->id())
                            <a href="{{ route('threads.edit', $thread) }}" class="text-blue-600 hover:text-blue-700">Edit</a>
                            <form action="{{ route('threads.destroy', $thread) }}" method="POST" class="inline" onsubmit="return confirm('Delete this thread?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-700">Delete</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>
</div>
@endsection