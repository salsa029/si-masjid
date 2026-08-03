<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\LogsAdminActivity;

class SacrificialAnimal extends Model
{
    use SoftDeletes, LogsAdminActivity;
    use HasFactory;

    protected $fillable = [
        'qurban_activity_id',
        'animal_type',
        'name',
        'package_name',
        'package_description',
        'weight',
        'age',
        'price',
        'photo',
        'max_participants',
        'status',
    ];

    /**
     * Relasi ke Qurban Activity (kegiatan/tahun kurban tempat hewan ini didaftarkan)
     */
    public function activity(): BelongsTo
    {
        return $this->belongsTo(QurbanActivity::class, 'qurban_activity_id');
    }

    /**
     * Relasi ke dokumentasi penyembelihan (SlaughterDocumentation)
     * Satu hewan bisa memiliki banyak dokumentasi
     */
    public function documentations(): HasMany
    {
        return $this->hasMany(SlaughterDocumentation::class, 'sacrificial_animal_id');
    }

    /**
     * Relasi ke pesanan kurban (QurbanOrder)
     * Satu hewan bisa memiliki banyak pesanan
     */
    public function orders(): HasMany
    {
        return $this->hasMany(QurbanOrder::class);
    }

    public function getBookedSlotsCountAttribute(): int
    {
        return $this->orders()->where('payment_status', 'success')->count();
    }

    public function getAvailableSlotsCountAttribute(): int
    {
        return max(0, $this->max_participants - $this->booked_slots_count);
    }

    public function getProgressPercentageAttribute(): int
    {
        if ($this->max_participants === 0) {
            return 0;
        }

        return (int) min(100, round($this->booked_slots_count / $this->max_participants * 100));
    }
}
