<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TryoutSeries extends Model
{
    protected $fillable = ['name', 'type', 'status'];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(TryoutSession::class);
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'questions', 'tryout_series_id', 'subject_id')->distinct();
    }
}