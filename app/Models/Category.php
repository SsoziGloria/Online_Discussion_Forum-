<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'thread_count',
        'is_locked',
    ];

    protected $casts = [
        'thread_count' => 'integer',
        'is_locked' => 'boolean',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Relationships
    public function threads()
    {
        return $this->hasMany(Thread::class);
    }

    public function getRecentThreadsAttribute()
    {
        return $this->threads()
            ->with('user')
            ->latest('last_activity_at')
            ->limit(5)
            ->get();
    }

    public function getLatestActivityAttribute()
    {
        $latestThread = $this->threads()
            ->whereNotNull('last_activity_at')
            ->latest('last_activity_at')
            ->first();

        if (!$latestThread) {
            return null;
        }

        return [
            'thread' => $latestThread,
            'time' => $latestThread->last_activity_at
        ];
    }

    // Helper method to generate slug
    public static function generateSlug($name)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (self::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    // Increment thread count
    public function incrementThreadCount()
    {
        $this->increment('thread_count');
    }

    // Decrement thread count
    public function decrementThreadCount()
    {
        $this->decrement('thread_count');
    }
}