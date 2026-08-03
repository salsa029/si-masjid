<?php

namespace App\Services;

use App\Models\Infaq;
use App\Models\TransactionCounter;
use App\Models\Zakat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DonationService
{
    /**
     * Menandai sebuah transaksi (Infaq ATAU Zakat) sebagai berhasil dibayar.
     * Dipanggil dari Webhook Midtrans MAUPUN Admin Verifikasi transfer manual.
     * Bersifat idempotent — aman dipanggil berkali-kali untuk transaksi yang sama.
     */
    public function markAsSuccess(Model $transaction, ?string $paymentMethod = null, ?int $verifiedByUserId = null): void
    {
        DB::transaction(function () use ($transaction, $paymentMethod, $verifiedByUserId) {
            $locked = $transaction::where('id', $transaction->id)->lockForUpdate()->first();

            if ($locked->payment_status === 'success') {
                return;
            }

            $locked->update([
                'payment_status' => 'success',
                'payment_method' => $paymentMethod ?? $locked->payment_method,
                'paid_at' => now(),
                'transaction_number' => $locked->transaction_number ?? $this->generateTransactionNumber($locked),
                'verified_by' => $verifiedByUserId,
                'verified_at' => $verifiedByUserId ? now() : null,
            ]);
        });
    }

    public function generateTransactionNumber(Model $transaction): string
    {
        $type = $transaction instanceof Infaq ? 'infaq' : 'zakat';
        $prefix = $transaction instanceof Infaq ? 'INF' : 'ZKT';

        return DB::transaction(function () use ($type, $prefix) {
            $year = now()->year;

            $counter = TransactionCounter::where('type', $type)->where('year', $year)->lockForUpdate()->first();

            if (! $counter) {
                $counter = TransactionCounter::create(['type' => $type, 'year' => $year, 'last_number' => 0]);
                $counter = TransactionCounter::where('id', $counter->id)->lockForUpdate()->first();
            }

            $nextNumber = $counter->last_number + 1;
            $counter->update(['last_number' => $nextNumber]);

            return sprintf('%s-%d-%05d', $prefix, $year, $nextNumber);
        });
    }

    /**
     * Melepas kembali reservasi transfer manual yang kedaluwarsa.
     * $modelClass diisi Infaq::class atau Zakat::class oleh pemanggil.
     */
    public function releaseExpiredBookings(string $modelClass): int
    {
        $expired = $modelClass::where('payment_status', 'pending')
            ->whereNotNull('reserved_until')
            ->where('reserved_until', '<', now())
            ->get();

        foreach ($expired as $transaction) {
            $transaction->update(['payment_status' => 'expired']);
        }

        return $expired->count();
    }
}
