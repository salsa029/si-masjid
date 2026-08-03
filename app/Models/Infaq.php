<?php

namespace App\Models;

use App\Traits\HasPaymentWorkflow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Infaq extends Model
{
    use SoftDeletes, HasPaymentWorkflow;

    protected $fillable = [
        'user_id',
        'category_id',
        'campaign_id',
        'transaction_number',
        'midtrans_order_id',
        'amount',
        'is_anonymous',
        'donor_name',
        'message',
        'payment_method',
        'payment_proof',
        'payment_status',
        'verification_note',
        'verified_by',
        'verified_at',
        'reserved_until',
        'paid_at',
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'paid_at' => 'datetime',
        'verified_at' => 'datetime',
        'reserved_until' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(InfaqCategory::class, 'category_id');
    }

    public function campaign()
    {
        return $this->belongsTo(InfaqCampaign::class, 'campaign_id');
    }
}
