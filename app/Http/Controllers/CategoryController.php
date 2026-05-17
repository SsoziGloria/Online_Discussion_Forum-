<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function __construct()
    {
        // Require login for posting and management actions
        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware('role:admin')->only(['edit', 'update', 'destroy']);
    }

    // Display all categories (forum home page)
    public function index()
    {
        $categories = Category::with(['threads' => function($query) {
                $query->latest('last_activity_at')->limit(1);
            }])
            ->orderBy('name')
            ->paginate(10);

        return view('categories.index', compact('categories'));
    }

    // Show threads in a specific category
    public function show(Category $category, Request $request)
    {
        $threads = $category->threads()
            ->with(['user', 'posts'])
            ->withCount(['posts as replies_count' => fn ($posts) => $posts->where('is_opening', false)])
            ->when($request->filled('sort'), function($query) use ($request) {
                if ($request->sort === 'latest') {
                    $query->latest('created_at');
                } elseif ($request->sort === 'oldest') {
                    $query->oldest('created_at');
                } elseif ($request->sort === 'most_replies') {
                    $query->orderBy('replies_count', 'desc');
                } elseif ($request->sort === 'most_votes') {
                    $query->orderBy('vote_score', 'desc');
                }
            }, function($query) {
                // Default: most recent activity
                $query->latest('last_activity_at');
            })
            ->paginate(20);

        return view('categories.show', compact('category', 'threads'));
    }

    // Show form to create new category (Admin only)
    public function create()
    {
        return view('categories.create');
    }

    // Store new category (Admin only)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|min:3|max:100|unique:categories,name',
            'description' => 'nullable|string|max:500'
        ]);

        $category = Category::create([
            'name' => $validated['name'],
            'slug' => Category::generateSlug($validated['name']),
            'description' => $validated['description'] ?? null,
            'thread_count' => 0
        ]);

        return redirect()
            ->route('categories.show', $category)
            ->with('success', 'Category "' . $category->name . '" created successfully!');
    }

    // Show form to edit category (Admin only)
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    // Update category (Admin only)
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'min:3',
                'max:100',
                Rule::unique('categories')->ignore($category->id)
            ],
            'description' => 'nullable|string|max:500'
        ]);

        // Update slug if name changed
        if ($category->name !== $validated['name']) {
            $validated['slug'] = Category::generateSlug($validated['name']);
        }

        $category->update($validated);

        return redirect()
            ->route('categories.show', $category)
            ->with('success', 'Category "' . $category->name . '" updated successfully!');
    }

    // Delete category (Admin only)
    public function destroy(Category $category)
    {
        // Check if category has threads
        if ($category->threads()->count() > 0) {
            return redirect()
                ->back()
                ->with('error', 'Cannot delete category that contains threads. Move or delete threads first.');
        }

        $categoryName = $category->name;
        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category "' . $categoryName . '" deleted successfully!');
    }
}
