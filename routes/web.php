<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ThreadController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ForumController::class, 'home'])->name('home');
Route::get('/search', [ForumController::class, 'search'])->name('search');
Route::get('/members/{user:username}', [CommunityController::class, 'show'])->name('members.show');

Route::get('/threads', [ThreadController::class, 'index'])->name('threads.index');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/threads/create/{category:slug?}', [ThreadController::class, 'create'])->name('threads.create');
    Route::post('/threads', [ThreadController::class, 'store'])->name('threads.store');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');

    Route::get('/threads/{thread:slug}/edit', [ThreadController::class, 'edit'])->name('threads.edit');
    Route::put('/threads/{thread:slug}', [ThreadController::class, 'update'])->name('threads.update');
    Route::delete('/threads/{thread:slug}', [ThreadController::class, 'destroy'])->name('threads.destroy');

    Route::middleware('role:admin')->group(function () {
        Route::get('/categories/{category:slug}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category:slug}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category:slug}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    });

    Route::post('/threads/{thread:slug}/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::post('/posts/{post}/vote', [PostController::class, 'vote'])->name('posts.vote');
    Route::get('/posts/{post}/report', [PostController::class, 'report'])->name('posts.report');
    Route::post('/posts/{post}/report', [PostController::class, 'storeReport'])->name('posts.report.store');
    Route::get('/posts/{post}/delete', [PostController::class, 'confirmDelete'])->name('posts.delete.confirm');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    Route::get('/notifications', [ForumController::class, 'notifications'])->name('notifications.index');
    Route::get('/moderation/flags', [ForumController::class, 'moderation'])->name('moderation.flags');
    Route::get('/settings/profile', [CommunityController::class, 'settings'])->name('settings.profile');

    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/admin/categories', [AdminController::class, 'categories'])->name('admin.categories');
});

Route::get('/categories/{category:slug}', [ForumController::class, 'category'])->name('categories.show');
Route::get('/threads/{thread:slug}', [ThreadController::class, 'show'])->name('threads.show');

Route::get('/dashboard', fn () => redirect()->route('home'))->name('dashboard');

require __DIR__.'/auth.php';
