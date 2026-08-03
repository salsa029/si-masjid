<?php

namespace App\Models;

use App\Traits\HasPaymentWorkflow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Zakat extends Model
{
    use SoftDeletes, HasPaymentWorkflow;

    protected $fillable = [
        'user_id',
        'zakat_type_id',
        'transaction_number',
        'midtrans_order_id',
        'snap_token',
        'snap_token_expires_at',
        'muzakki_name',
        'number_of_souls',
        'calculation_base',
        'is_above_nishab',
        'amount',
        'is_anonymous',
        'message',
        'payment_method',
        'payment_proof',
        'payment_status',
        'verification_note',
        'deletion_reason',
        'verified_by',
        'verified_at',
        'reserved_until',
        'paid_at',
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'is_above_nishab' => 'boolean',
        'paid_at' => 'datetime',
        'verified_at' => 'datetime',
        'reserved_until' => 'datetime',
        'snap_token_expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function zakatType()
    {
        return $this->belongsTo(ZakatType::class);
    }
}
