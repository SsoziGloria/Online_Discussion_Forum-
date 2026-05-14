<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Warning extends Model
{
    protected $fillable = [
        'user_id',
        'issued_by',
        'reason',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    /** The user who received this warning */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** The moderator who issued this warning */
    public function issuer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }
}
