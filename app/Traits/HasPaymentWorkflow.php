<?php

namespace App\Traits;

use App\Models\TransactionStatusHistory;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

/**
 * Trait HasPaymentWorkflow
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait HasPaymentWorkflow
{
    /**
     * Didaftarkan otomatis oleh Laravel sekali per class model (class level), bukan
     * per instance — supaya listener 'updating' tidak menumpuk/terdaftar berkali-kali
     * setiap kali model di-instantiate ulang dalam satu request.
     */
    public static function bootHasPaymentWorkflow(): void
    {
        static::updating(function ($model) {
            if ($model->isDirty('payment_status')) {
                $model->statusHistories()->create([
                    'from_status' => $model->getOriginal('payment_status'),
                    'to_status' => $model->payment_status,
                    'changed_by' => Auth::id(),
                ]);
            }
        });
    }

    public function statusHistories()
    {
        return $this->morphMany(TransactionStatusHistory::class, 'transactionable');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function scopeSuccess(Builder $query): Builder
    {
        return $query->where('payment_status', 'success');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('payment_status', 'pending');
    }

    public function scopeAwaitingVerification(Builder $query): Builder
    {
        return $query->where('payment_status', 'awaiting_verification');
    }

    public function getDisplayNameAttribute(): string
    {
        // Menggunakan getAttribute() membungkam error 'Undefined property'
        // karena IDE membaca ini sebagai pemanggilan method resmi bawaan Eloquent.
        if ($this->getAttribute('is_anonymous')) {
            return 'Hamba Allah';
        }

        return $this->getAttribute('donor_name')
            ?? $this->getAttribute('muzakki_name')
            ?? ($this->getAttribute('user') ? $this->getAttribute('user')->name : '');
    }
}
