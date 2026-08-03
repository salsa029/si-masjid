<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsAdminActivity;

class DonationSetting extends Model
{
    use LogsAdminActivity;
    protected $fillable = [
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'qris_image',
        'min_infaq_amount',
        'min_zakat_amount',
        'rice_price_per_kg',
        'gold_price_per_gram',
        'thank_you_message',
        'confirmation_whatsapp',
    ];
}
