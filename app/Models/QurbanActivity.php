<?php

namespace App\Models;

use App\Traits\LogsAdminActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class QurbanActivity extends Model
{
    use SoftDeletes, LogsAdminActivity, HasFactory;

    protected $fillable = [
        'name',
        'date',
        'description',
        'dkm_chairman_name',
        'dkm_chairman_signature',
        'qurban_chairman_name',
        'qurban_chairman_photo',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function animals(): HasMany
    {
        return $this->hasMany(SacrificialAnimal::class);
    }

    public function getTotalBalanceKgAttribute(): float
    {
        return (float) $this->animals()->sum('weight');
    }
}
