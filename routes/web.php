<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ForumController::class, 'home'])->name('home');
Route::get('/categories/{category:slug}', [ForumController::class, 'category'])->name('categories.show');
Route::get('/threads/create', [ForumController::class, 'create'])->name('threads.create');
Route::get('/threads/{thread:slug}', [ForumController::class, 'thread'])->name('threads.show');
Route::get('/search', [ForumController::class, 'search'])->name('search');
Route::get('/notifications', [ForumController::class, 'notifications'])->name('notifications.index');
Route::get('/moderation/flags', [ForumController::class, 'moderation'])->name('moderation.flags');

Route::get('/members/{user:username}', [CommunityController::class, 'show'])->name('members.show');
Route::get('/settings/profile', [CommunityController::class, 'settings'])->name('settings.profile');

Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
Route::get('/admin/categories', [AdminController::class, 'categories'])->name('admin.categories');

Route::middleware('auth')->group(function () {
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::get('/posts/{post}/report', [PostController::class, 'report'])->name('posts.report');
    Route::post('/posts/{post}/report', [PostController::class, 'storeReport'])->name('posts.report.store');
    Route::get('/posts/{post}/delete', [PostController::class, 'confirmDelete'])->name('posts.delete.confirm');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
});

Route::get('/dashboard', fn () => redirect()->route('home'))->name('dashboard');

require __DIR__.'/auth.php';
