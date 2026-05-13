@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-emerald-50 py-12">
    <div class="max-w-3xl mx-auto px-6">
        <div class="bg-white rounded-3xl shadow-xl p-10">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">✏️ Edit Discussion</h1>
            <p class="text-gray-600 mb-8">Update your thread below</p>

            <form method="POST" action="{{ route('threads.update', $thread) }}">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Thread Title</label>
                    <input type="text" name="title" value="{{ old('title', $thread->title) }}" 
                           class="w-full px-5 py-4 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-emerald-500 text-lg" required>
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Discussion Content</label>
                    <textarea name="body" rows="10" 
                              class="w-full px-5 py-4 border border-gray-300 rounded-3xl focus:ring-2 focus:ring-emerald-500" required>{{ old('body', $thread->body) }}</textarea>
                </div>

                <div class="flex gap-4">
                    <a href="{{ route('threads.show', $thread) }}" 
                       class="px-8 py-4 text-gray-600 hover:bg-gray-100 rounded-2xl font-medium">Cancel</a>
                    <button type="submit" 
                            class="flex-1 bg-gradient-to-r from-emerald-600 to-blue-600 text-white py-4 rounded-2xl font-semibold text-lg">
                        Update Discussion
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection