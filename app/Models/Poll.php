<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    use HasFactory;

    protected $fillable = ['chip_id', 'question', 'allow_multiple_votes', 'expires_at'];

    protected $casts = [
        'expires_at' => 'datetime',
        'allow_multiple_votes' => 'boolean',
    ];

    public function chip()
    {
        return $this->belongsTo(Chip::class);
    }

    public function options()
    {
        return $this->hasMany(PollOption::class);
    }

    public function votes()
    {
        return $this->hasManyThrough(PollVote::class, PollOption::class);
    }
}
