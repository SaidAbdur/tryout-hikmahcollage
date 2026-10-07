<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subject extends Model
{
    protected $fillable = [
        'name', 'type', 'duration_minutes', 'live_discussion_schedule'
    ];

    protected $casts = [
        'live_discussion_schedule' => 'datetime',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function tryoutSessions()
    {
        return $this->hasMany(TryoutSession::class);
    }

    public function tryoutSeries(): BelongsToMany
    {
        return $this->belongsToMany(TryoutSeries::class, 'questions', 'subject_id', 'tryout_series_id')->distinct();
    }
}
