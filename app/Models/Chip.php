<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chip extends Model
{
    protected $fillable = [
        'message',
        'parent_id',
        'user_id',
        'media'
    ];

    protected $casts = [
        'media' => 'array',
    ];

    public function replies() {
        return $this->hasMany(Chip::class, 'parent_id')
            ->with('user', 'replies.user');
    }

    public function parent() {
        return $this->belongsTo( Chip::class, 'parent_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
