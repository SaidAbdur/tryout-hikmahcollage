<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    protected $fillable = ['name', 'type', 'duration_minutes'];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(TryoutSession::class);
    }
}
