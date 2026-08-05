<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QurbanInstallment extends Model
{
    protected $fillable = [
        'qurban_order_id',
        'installment_number',
        'amount',
        'due_date',
        'payment_status',
        'midtrans_order_id',
        'snap_token',
        'snap_token_expires_at',
        'payment_proof',
        'verification_note',
        'verified_by',
        'verified_at',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'snap_token_expires_at' => 'datetime',
            'verified_at' => 'datetime',
            'paid_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(QurbanOrder::class, 'qurban_order_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function isOverdue(): bool
    {
        return $this->payment_status !== 'success' && $this->due_date->isPast();
    }
}
