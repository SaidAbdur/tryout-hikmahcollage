<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'subject_id', 'tryout_series_id', 'question_text', 'option_a', 'option_b', 'option_c', 'option_d', 'option_e', 'correct_option', 'explanation_text'
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function tryoutSeries()
    {
        return $this->belongsTo(TryoutSeries::class);
    }

    public function options(): array
    {
        return collect([
            'A' => $this->option_a,
            'B' => $this->option_b,
            'C' => $this->option_c,
            'D' => $this->option_d,
            'E' => $this->option_e,
        ])->filter(fn ($option) => filled($option))->all();
    }
}
