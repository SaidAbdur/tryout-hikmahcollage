<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentAnswer extends Model
{
    protected $fillable = ['tryout_session_id', 'question_id', 'selected_option', 'is_correct', 'is_flagged'];

    protected function casts(): array
    {
        return ['is_correct' => 'boolean', 'is_flagged' => 'boolean'];
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(TryoutSession::class, 'tryout_session_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
