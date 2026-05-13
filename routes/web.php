<?php

use App\Http\Controllers\ThreadController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

// ====================== PUBLIC ROUTES ======================
Route::get('/', [ThreadController::class, 'index'])->name('home');

// ====================== AUTHENTICATED ROUTES ======================
Route::middleware('auth')->group(function () {

    // Threads CRUD
    Route::resource('threads', ThreadController::class);

    // Replies (Posts)
    Route::post('/threads/{thread}/posts', [PostController::class, 'store'])
         ->name('posts.store');

    Route::delete('/posts/{post}', [PostController::class, 'destroy'])
         ->name('posts.destroy');

    // Dashboard redirect
    Route::get('/dashboard', function () {
        return redirect()->route('home');
    })->name('dashboard');
});

require __DIR__.'/auth.php';