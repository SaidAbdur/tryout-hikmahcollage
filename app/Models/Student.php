<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'student_id', 'password', 'name', 'phone', 'region', 'school', 'grade_level', 'dob', 'age', 'gender', 'parent_name', 'parent_phone', 'parent_email', 'package_type', 'proof_files', 'account_status'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'proof_files' => 'array',
    ];

    public function tryoutSessions()
    {
        return $this->hasMany(TryoutSession::class);
    }

    public function sessions()
    {
        return $this->tryoutSessions();
    }
}
