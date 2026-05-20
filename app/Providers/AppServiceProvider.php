<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Flag;
use App\Models\Thread;
use App\Models\Post;
use App\Policies\CategoryPolicy;
use App\Policies\FlagPolicy;
use App\Policies\ThreadPolicy;
use App\Policies\PostPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Category::class => CategoryPolicy::class,
        Flag::class => FlagPolicy::class,
        //Thread::class => ThreadPolicy::class,
        //Post::class => PostPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define a gate for moderator actions
        Gate::define('moderate', function ($user) {
            return in_array($user->role, ['moderator', 'admin']);
        });

        // Define a gate for admin-only actions
        Gate::define('admin', function ($user) {
            return $user->role === 'admin';
        });

        // Define a gate for checking if user is verified (from SRS FR-01)
        Gate::define('verified', function ($user) {
            return $user->email_verified_at !== null;
        });

        // Define a gate for checking if user is not banned (from SRS FR-51)
        Gate::define('not-banned', function ($user) {
            return !$user->is_banned;
        });
    }
}
