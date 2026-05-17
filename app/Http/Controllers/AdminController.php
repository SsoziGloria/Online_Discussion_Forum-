<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

        public function users(Request $request): View
    {
        $query = trim((string) $request->string('q'));

        $users = User::query()
            ->withCount([
                'threads',
                'posts as replies_count' => fn ($posts) => $posts->where('is_opening', false),
            ])
            ->when($query !== '', function ($builder) use ($query) {
                $builder->where('name', 'like', "%{$query}%")
                        ->orWhere('email', 'like', "%{$query}%")
                        ->orWhere('display_name', 'like', "%{$query}%");
            })
            ->latest()
            ->paginate(15);

        return view('forum.admin.users', [
            'users' => $users,
            'query' => $query,
            'isAdmin' => auth()->user()?->isAdmin() ?? false,   // Pass this to view
        ]);
    }

    public function categories(Request $request): View
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized. Admin access only.');
        }

        $selectedCategory = $request->filled('category')
            ? Category::whereKey($request->integer('category'))->first()
            : null;

        $categories = Category::withCount('threads')
            ->orderByDesc('threads_count')
            ->orderBy('name')
            ->paginate(10);

        return view('forum.admin.categories', [
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
        ]);
    }
}
