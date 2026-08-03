<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QurbanOrder extends Model
{
    protected $fillable = [
        'user_id',
        'sacrificial_animal_id',
        'order_type',
        'total_amount',
        'midtrans_order_id',
        'invoice_number',
        'certificate_number',
        'payment_status',
        'payment_method',
        'payment_proof',
        'verification_note',
        'verified_by',
        'verified_at',
        'reserved_until',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'verified_at' => 'datetime',
        'reserved_until' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function animal(): BelongsTo
    {
        return $this->belongsTo(SacrificialAnimal::class, 'sacrificial_animal_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function participants(): HasMany
    {
        return $this->hasMany(QurbanParticipant::class);
    }
}
