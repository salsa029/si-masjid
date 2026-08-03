<?php

namespace App\Models;

use App\Traits\LogsAdminActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;


class InfaqCampaign extends Model
{
    use SoftDeletes, LogsAdminActivity;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'description',
        'thumbnail',
        'target_amount',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (InfaqCampaign $campaign) {
            $campaign->slug = $campaign->slug ?: Str::slug($campaign->title);
        });
    }

    public function category()
    {
        return $this->belongsTo(InfaqCategory::class, 'category_id');
    }

    public function infaqs()
    {
        return $this->hasMany(Infaq::class, 'campaign_id');
    }

    public function getCollectedAmountAttribute(): float
    {
        return $this->infaqs()->where('payment_status', 'success')->sum('amount');
    }

    public function getProgressPercentageAttribute(): int
    {
        if ((float) $this->target_amount <= 0) {
            return 0;
        }

        return (int) min(100, round($this->collected_amount / $this->target_amount * 100));
    }
}
