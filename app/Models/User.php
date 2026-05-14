<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name',
    'username',
    'display_name',
    'email',
    'password',
    'role',
    'bio',
    'avatar_url',
    'is_banned',
    'banned_at',
    'reputation',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'banned_at' => 'datetime',
            'is_banned' => 'boolean',
            'password' => 'hashed',
        ];
    }

    // Relationships

    public function threads(): HasMany
    {
        return $this->hasMany(Thread::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    /** Flags this user has filed against posts */
    public function reportedFlags(): HasMany
    {
        return $this->hasMany(Flag::class, 'reported_by');
    }

    /** Flags this moderator has resolved */
    public function resolvedFlags(): HasMany
    {
        return $this->hasMany(Flag::class, 'resolved_by');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /** Warnings issued to this user */
    public function warnings(): HasMany
    {
        return $this->hasMany(Warning::class, 'user_id');
    }

    /** Warnings this moderator has issued to others */
    public function issuedWarnings(): HasMany
    {
        return $this->hasMany(Warning::class, 'issued_by');
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isModerator(): bool
    {
        return in_array($this->role, ['admin', 'moderator']);
    }

    public function isBanned(): bool
    {
        return $this->is_banned;
    }
}
