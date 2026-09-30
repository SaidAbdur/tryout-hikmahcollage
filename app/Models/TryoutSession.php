<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TryoutSession extends Model
{
    protected $fillable = [
        'student_id', 'subject_id', 'score', 'total_correct', 'total_wrong',
        'started_at', 'submitted_at', 'status',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'submitted_at' => 'datetime',
            'score' => 'float',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(StudentAnswer::class);
    }

    /* ---- Timer: the server is the source of truth, the browser only displays it ---- */

    public function deadline(): Carbon
    {
        return $this->started_at->copy()->addMinutes($this->subject->duration_minutes);
    }

    public function remainingSeconds(): int
    {
        return max(0, $this->deadline()->timestamp - now()->timestamp);
    }

    public function isExpired(): bool
    {
        return $this->status === 'in_progress' && $this->deadline()->isPast();
    }

    /** Grade the session. Safe to call twice; also used to close sessions whose tab was abandoned. */
    public function finalize(): void
    {
        if ($this->status === 'completed') {
            return;
        }

        $total = $this->subject->questions()->count();
        $correct = $this->answers()->where('is_correct', true)->count();
        $wrong = $this->answers()->whereNotNull('selected_option')->where('is_correct', false)->count();

        $this->update([
            'status' => 'completed',
            'submitted_at' => now()->min($this->deadline()),
            'total_correct' => $correct,
            'total_wrong' => $wrong,
            'score' => $total ? round($correct / $total * 100, 2) : 0,
        ]);
    }

    /** [emoji, label, tailwind classes] — the gamified badge shown next to a score. */
    public function badge(): array
    {
        $s = $this->score ?? 0;

        return match (true) {
            $s >= 85 => ['🏆', 'Juara Tryout', 'bg-amber-100 text-amber-700'],
            $s >= 70 => ['🥈', 'Bintang Hebat', 'bg-sky-100 text-sky-700'],
            $s >= 50 => ['🌟', 'Terus Semangat', 'bg-emerald-100 text-emerald-700'],
            default => ['🌱', 'Ayo Coba Lagi', 'bg-rose-100 text-rose-600'],
        };
    }
}
