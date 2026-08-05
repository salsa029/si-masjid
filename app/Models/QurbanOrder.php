<?php

namespace App\Models;

use App\Traits\HasPaymentWorkflow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class QurbanOrder extends Model
{
    use SoftDeletes, HasPaymentWorkflow;

    protected $fillable = [
        'user_id',
        'sacrificial_animal_id',
        'order_type',
        'total_amount',
        'midtrans_order_id',
        'snap_token',
        'snap_token_expires_at',
        'invoice_number',
        'certificate_number',
        'payment_status',
        'payment_method',
        'payment_proof',
        'verification_note',
        'deletion_reason',
        'verified_by',
        'verified_at',
        'reserved_until',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'paid_at' => 'datetime',
            'verified_at' => 'datetime',
            'reserved_until' => 'datetime',
            'snap_token_expires_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function animal(): BelongsTo
    {
        return $this->belongsTo(SacrificialAnimal::class, 'sacrificial_animal_id');
    }

    public function participants(): HasMany
    {
        return $this->hasMany(QurbanParticipant::class);
    }
}
