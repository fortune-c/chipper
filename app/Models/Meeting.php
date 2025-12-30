<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $fillable = [
        'title',
        'starts_at',
        'meeting_link',
        'description',
        'user_id',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
