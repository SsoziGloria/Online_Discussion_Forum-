@extends('layouts.app')

@section('title', 'Forum Categories')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Discussion Categories</h1>
        @can('create', App\Models\Category::class)
            <a href="{{ route('categories.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                + New Category
            </a>
        @endcan
    </div>

    <div class="space-y-4">
        @forelse($categories as $category)
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h2 class="text-2xl font-semibold mb-2">
                                <a href="{{ route('categories.show', $category) }}" 
                                   class="text-blue-600 hover:text-blue-800 transition">
                                    {{ $category->name }}
                                </a>
                            </h2>
                            <p class="text-gray-600 mb-3">{{ $category->description ?? 'No description available.' }}</p>
                            
                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                <span>
                                    📊 {{ $category->thread_count }} {{ Str::plural('thread', $category->thread_count) }}
                                </span>
                                
                                @if($latestActivity = $category->latest_activity)
                                    <span>
                                        💬 Latest: 
                                        <a href="{{ route('threads.show', $latestActivity['thread']) }}" 
                                           class="text-blue-500 hover:underline">
                                            {{ Str::limit($latestActivity['thread']->title, 40) }}
                                        </a>
                                    </span>
                                    <span>
                                        🕒 {{ $latestActivity['time']->diffForHumans() }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        @can('update', $category)
                            <div class="flex space-x-2 ml-4">
                                <a href="{{ route('categories.edit', $category) }}" 
                                   class="text-gray-500 hover:text-blue-600 transition">
                                    ✏️ Edit
                                </a>
                                <form action="{{ route('categories.destroy', $category) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this category?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-500 hover:text-red-600 transition">
                                        🗑️ Delete
                                    </button>
                                </form>
                            </div>
                        @endcan
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <p class="text-gray-500 text-lg">No categories found.</p>
                @can('create', App\Models\Category::class)
                    <a href="{{ route('categories.create') }}" class="text-blue-600 hover:underline mt-2 inline-block">
                        Create the first category →
                    </a>
                @endcan
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $categories->links() }}
    </div>
</div>
@endsection