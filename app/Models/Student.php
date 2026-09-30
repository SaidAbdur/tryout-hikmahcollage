<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Validation\Rule;

class Student extends Authenticatable
{
    public const GRADES = [
        'Kelas 6 (SD)', 'Kelas 9 (SMP)', 'Kelas 10 (SMA)',
        'Kelas 11 (SMA)', 'Kelas 12 (SMA)', 'Lainnya',
    ];

    protected $fillable = [
        'student_id', 'name', 'dob', 'class_grade', 'school_name',
        'address', 'parent_name', 'parent_wa', 'parent_email',
    ];

    protected function casts(): array
    {
        return ['dob' => 'date', 'email_verified_at' => 'datetime'];
    }

    /** Shared by public registration and the admin edit form. */
    public static function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'dob' => ['required', 'date', 'before:today'],
            'class_grade' => ['required', Rule::in(self::GRADES)],
            'school_name' => ['required', 'string', 'max:150'],
            'address' => ['required', 'string', 'max:255'],
            'parent_name' => ['required', 'string', 'max:120'],
            'parent_wa' => ['required', 'regex:/^(\+62|62|0)8[0-9]{8,12}$/'],
            'parent_email' => ['nullable', 'email', 'max:150'],
        ];
    }

    /** STU-2026-XXXX. Random suffix (no 0/O/1/I) so IDs can't be guessed by counting up. */
    public static function generateStudentId(): string
    {
        $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

        do {
            $suffix = '';
            for ($i = 0; $i < 4; $i++) {
                $suffix .= $alphabet[random_int(0, strlen($alphabet) - 1)];
            }
            $id = 'STU-'.now()->year.'-'.$suffix;
        } while (static::where('student_id', $id)->exists());

        return $id;
    }

    public function scopeFilter(Builder $query, array $f): Builder
    {
        return $query
            ->when($f['q'] ?? null, fn ($q, $s) => $q->where(fn ($w) => $w
                ->where('name', 'like', "%{$s}%")
                ->orWhere('student_id', 'like', "%{$s}%")
                ->orWhere('parent_name', 'like', "%{$s}%")))
            ->when($f['grade'] ?? null, fn ($q, $v) => $q->where('class_grade', $v))
            ->when($f['school'] ?? null, fn ($q, $v) => $q->where('school_name', 'like', "%{$v}%"))
            ->when($f['from'] ?? null, fn ($q, $v) => $q->whereDate('created_at', '>=', $v))
            ->when($f['to'] ?? null, fn ($q, $v) => $q->whereDate('created_at', '<=', $v));
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(TryoutSession::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}
