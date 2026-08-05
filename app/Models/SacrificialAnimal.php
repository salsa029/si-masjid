<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
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

    /**
     * Semua cicilan (QurbanInstallment) milik pesanan-pesanan atas hewan ini.
     */
    public function installments(): HasManyThrough
    {
        return $this->hasManyThrough(QurbanInstallment::class, QurbanOrder::class, 'sacrificial_animal_id', 'qurban_order_id');
    }

    /**
     * Eager-load semua data yang dibutuhkan getBookedSlotsCountAttribute() dan
     * getCollectedAmountAttribute() sekaligus, supaya kedua accessor itu tidak N+1
     * saat dipakai di halaman daftar hewan kurban.
     */
    public function scopeWithBookingStats(Builder $query): Builder
    {
        return $query
            ->withCount([
                'orders as has_full_order_count' => fn ($q) => $q->where('payment_status', 'success')->where('order_type', 'full'),
                'orders as patungan_slots_count' => fn ($q) => $q->where('payment_status', 'success')->where('order_type', 'patungan'),
            ])
            ->withSum(['orders as success_amount' => fn ($q) => $q->where('payment_status', 'success')], 'total_amount')
            ->withSum(['installments as in_progress_installment_amount' => fn ($q) => $q->where('qurban_installments.payment_status', 'success')
                ->whereHas('order', fn ($oq) => $oq->where('qurban_orders.payment_status', 'pending'))], 'amount');
    }

    /**
     * Jumlah slot terpakai. Pesanan 'full' (beli sendirian) menghabiskan SEMUA slot,
     * bukan cuma 1 — supaya hewan yang sudah dibeli penuh tidak bisa lagi dipatungan
     * orang lain (lihat guard di QurbanOrderController::store()).
     */
    public function getBookedSlotsCountAttribute(): int
    {
        // Pakai hasil withCount() kalau sudah di-eager-load, supaya tidak query ulang
        // per baris (N+1) saat menampilkan daftar hewan kurban.
        if (array_key_exists('has_full_order_count', $this->attributes) && array_key_exists('patungan_slots_count', $this->attributes)) {
            return ((int) $this->attributes['has_full_order_count']) > 0
                ? $this->max_participants
                : (int) $this->attributes['patungan_slots_count'];
        }

        $hasFullOrder = $this->orders()->where('payment_status', 'success')->where('order_type', 'full')->exists();

        if ($hasFullOrder) {
            return $this->max_participants;
        }

        return $this->orders()->where('payment_status', 'success')->where('order_type', 'patungan')->count();
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

    /**
     * Total dana yang sudah terkumpul untuk hewan ini: pesanan yang sudah lunas penuh,
     * ditambah cicilan yang sudah dibayar dari pesanan yang masih berjalan (belum lunas semua).
     */
    public function getCollectedAmountAttribute(): float
    {
        if (array_key_exists('success_amount', $this->attributes) && array_key_exists('in_progress_installment_amount', $this->attributes)) {
            return (float) $this->attributes['success_amount'] + (float) $this->attributes['in_progress_installment_amount'];
        }

        $successAmount = (float) $this->orders()->where('payment_status', 'success')->sum('total_amount');

        $inProgressInstallmentAmount = (float) $this->installments()
            ->where('qurban_installments.payment_status', 'success')
            ->whereHas('order', fn ($query) => $query->where('qurban_orders.payment_status', 'pending'))
            ->sum('amount');

        return $successAmount + $inProgressInstallmentAmount;
    }

    public function getAmountProgressPercentageAttribute(): int
    {
        if ((float) $this->price <= 0) {
            return 0;
        }

        return (int) min(100, round($this->collected_amount / $this->price * 100));
    }
}
