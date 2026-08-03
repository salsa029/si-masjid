<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\LogsAdminActivity;

class ZakatType extends Model
{
    use LogsAdminActivity;
    protected $fillable = [
        'name',
        'slug',
        'description',
        'calculation_unit',
        'nishab_amount',
        'nishab_description',
        'rate_percentage',
    ];

    protected static function booted(): void
    {
        static::creating(function (ZakatType $type) {
            $type->slug = $type->slug ?: Str::slug($type->name);
        });
    }

    public function zakats()
    {
        return $this->hasMany(Zakat::class);
    }
}
