<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function users(Request $request): View
    {
        $query = trim((string) $request->string('q'));

        $users = User::query()
            ->withCount(['threads', 'posts', 'warnings'])
            ->when($query !== '', function ($builder) use ($query) {
                $builder->where(function ($inner) use ($query) {
                    $inner->where('username', 'like', "%{$query}%")
                        ->orWhere('display_name', 'like', "%{$query}%")
                        ->orWhere('email', 'like', "%{$query}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('forum.admin.users', [
            'users' => $users,
            'query' => $query,
        ]);
    }

    public function categories(Request $request): View
    {
        $categories = Category::query()
            ->withCount('threads')
            ->orderByDesc('threads_count')
            ->orderBy('name')
            ->paginate(10);

        $selectedCategory = Category::query()
            ->whereKey($request->integer('category'))
            ->first() ?? $categories->getCollection()->first();

        return view('forum.admin.categories', [
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
        ]);
    }
}
