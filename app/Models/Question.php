<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model
{
    protected $fillable = [
        'subject_id', 'question_text', 'option_a', 'option_b', 'option_c',
        'option_d', 'option_e', 'correct_option', 'explanation_text',
    ];

    /** ['A' => '...', 'B' => '...'] — option E is optional, so empty options are dropped. */
    public function options(): array
    {
        return array_filter([
            'A' => $this->option_a,
            'B' => $this->option_b,
            'C' => $this->option_c,
            'D' => $this->option_d,
            'E' => $this->option_e,
        ], fn ($v) => filled($v));
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}
