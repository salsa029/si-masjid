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
        'payment_type',
        'installment_count',
        'installment_deadline',
        'refund_requested',
        'refund_requested_at',
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
            'installment_deadline' => 'date',
            'refund_requested' => 'boolean',
            'refund_requested_at' => 'datetime',
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

    public function installments(): HasMany
    {
        return $this->hasMany(QurbanInstallment::class)->orderBy('installment_number');
    }

    public function isInstallment(): bool
    {
        return $this->payment_type === 'installment';
    }

    /**
     * Cicilan berikutnya yang belum lunas (null jika sudah lunas semua atau bukan pesanan cicilan).
     */
    public function getNextInstallmentAttribute(): ?QurbanInstallment
    {
        if (! $this->isInstallment()) {
            return null;
        }

        return $this->installments->firstWhere('payment_status', '!=', 'success');
    }

    /**
     * Total yang sudah dibayar sejauh ini (menjumlahkan cicilan sukses, atau total_amount jika lunas penuh).
     */
    public function getAmountPaidAttribute(): float
    {
        if ($this->isInstallment()) {
            return (float) $this->installments->where('payment_status', 'success')->sum('amount');
        }

        return $this->payment_status === 'success' ? (float) $this->total_amount : 0.0;
    }
}
