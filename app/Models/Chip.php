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

    public function reactions()
    {
        return $this->morphMany(Reaction::class, 'reactable');
    }

    public function getHtmlMessageAttribute()
    {
        $message = e($this->message);
        return preg_replace('/@([A-Za-z0-9_]+)/', '<a href="#" class="text-primary font-bold hover:underline">@$1</a>', $message);
    }

    public function poll()
    {
        return $this->hasOne(Poll::class);
    }
}
