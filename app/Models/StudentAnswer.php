<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    protected $fillable = [
        'tryout_session_id', 'question_id', 'answered_option', 'is_correct', 'is_flagged'
    ];

    public function session()
    {
        return $this->belongsTo(TryoutSession::class, 'tryout_session_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
