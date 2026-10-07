<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TryoutSession extends Model
{
    protected $fillable = [
        'student_id', 'subject_id', 'tryout_series_id', 'score', 'started_at', 'submitted_at', 'status'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function tryoutSeries()
    {
        return $this->belongsTo(TryoutSeries::class);
    }
    
    public function answers()
    {
        return $this->hasMany(StudentAnswer::class);
    }

    public function deadline(): Carbon
    {
        return $this->started_at?->copy()->addMinutes($this->subject->duration_minutes) ?? now();
    }

    public function remainingSeconds(): int
    {
        return max(0, $this->deadline()->getTimestamp() - now()->getTimestamp());
    }

    public function isExpired(): bool
    {
        return $this->started_at !== null && $this->remainingSeconds() === 0;
    }

    public function finalize(): void
    {
        if ($this->status === 'completed') {
            return;
        }

        $this->loadMissing(['subject', 'answers']);
        $total = $this->subject->questions()->where('tryout_series_id', $this->tryout_series_id)->count();
        $correct = $this->answers->where('is_correct', true)->count();

        $this->update([
            'status' => 'completed',
            'submitted_at' => now(),
            'score' => $total > 0 ? round(($correct / $total) * 100) : 0,
        ]);
    }

    public function getTotalCorrectAttribute(): int
    {
        return $this->answers()->where('is_correct', true)->count();
    }

    public function getTotalWrongAttribute(): int
    {
        return $this->answers()->whereNotNull('answered_option')->where('is_correct', false)->count();
    }

    public function badge(): array
    {
        return match (true) {
            $this->score >= 80 => ['🌟', 'Sangat baik', 'bg-emerald-100 text-emerald-700'],
            $this->score >= 60 => ['👍', 'Terus berkembang', 'bg-sky-100 text-sky-700'],
            default => ['📘', 'Tetap semangat', 'bg-amber-100 text-amber-700'],
        };
    }
}
