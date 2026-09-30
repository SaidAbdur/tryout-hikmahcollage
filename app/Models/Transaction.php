<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'student_id', 'package_id', 'transaction_code', 'gross_amount',
        'payment_status', 'payment_gateway_ref', 'paid_at',
    ];

    protected function casts(): array
    {
        return ['gross_amount' => 'decimal:2', 'paid_at' => 'datetime'];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
}
