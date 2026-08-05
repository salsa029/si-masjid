<?php

namespace App\Models;

use App\Traits\LogsAdminActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class QurbanActivity extends Model
{
    use SoftDeletes, LogsAdminActivity, HasFactory;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'description',
        'dkm_chairman_name',
        'dkm_chairman_signature',
        'qurban_chairman_name',
        'qurban_chairman_photo',
        'certificate_background',
        'certificate_name_top',
        'certificate_name_left',
        'certificate_name_font_size',
        'certificate_year_top',
        'certificate_year_left',
        'certificate_year_font_size',
        'certificate_animal_top',
        'certificate_animal_left',
        'certificate_animal_font_size',
        'certificate_dkm_name_top',
        'certificate_dkm_name_left',
        'certificate_dkm_name_font_size',
        'certificate_panitia_name_top',
        'certificate_panitia_name_left',
        'certificate_panitia_name_font_size',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'certificate_name_top' => 'float',
            'certificate_name_left' => 'float',
            'certificate_year_top' => 'float',
            'certificate_year_left' => 'float',
            'certificate_animal_top' => 'float',
            'certificate_animal_left' => 'float',
            'certificate_dkm_name_top' => 'float',
            'certificate_dkm_name_left' => 'float',
            'certificate_panitia_name_top' => 'float',
            'certificate_panitia_name_left' => 'float',
        ];
    }

    public function animals(): HasMany
    {
        return $this->hasMany(SacrificialAnimal::class);
    }

    public function getTotalBalanceKgAttribute(): float
    {
        return (float) $this->animals()->sum('weight');
    }

    /**
     * Kegiatan otomatis tampil di katalog publik begitu tanggal mulai tiba,
     * dan otomatis tutup begitu tanggal selesai terlewati.
     */
    public function hasStarted(): bool
    {
        return $this->start_date === null || ! $this->start_date->copy()->startOfDay()->isFuture();
    }

    public function hasEnded(): bool
    {
        return $this->end_date !== null && $this->end_date->copy()->endOfDay()->isPast();
    }

    public function isRegistrationOpen(): bool
    {
        return $this->hasStarted() && ! $this->hasEnded();
    }

    protected function isOpen(): Attribute
    {
        return Attribute::get(fn () => $this->isRegistrationOpen());
    }

    /**
     * Status kegiatan untuk ditampilkan di UI admin: upcoming | active | ended.
     */
    protected function statusLabel(): Attribute
    {
        return Attribute::get(function () {
            if (! $this->hasStarted()) {
                return 'upcoming';
            }

            return $this->hasEnded() ? 'ended' : 'active';
        });
    }

    public function scopeOpen(Builder $query): Builder
    {
        $today = now()->toDateString();

        return $query
            ->where(fn ($q) => $q->whereNull('start_date')->orWhereDate('start_date', '<=', $today))
            ->where(fn ($q) => $q->whereNull('end_date')->orWhereDate('end_date', '>=', $today));
    }
}
