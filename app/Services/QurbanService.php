<?php

namespace App\Services;

use App\Models\QurbanInvoiceCounter;
use App\Models\QurbanOrder;
use App\Models\SacrificialAnimal;
use Illuminate\Support\Facades\DB;

class QurbanService
{
    /**
     * Menandai sebuah pesanan kurban sebagai berhasil dibayar.
     * Dipanggil dari Webhook Midtrans (otomatis) MAUPUN Admin Verifikasi (manual transfer).
     *
     * Bersifat idempotent: jika dipanggil berkali-kali untuk order yang sama,
     * hanya proses pertama yang benar-benar berefek.
     */
    public function markOrderAsSuccess(QurbanOrder $order, ?string $paymentMethod = null, ?int $verifiedByUserId = null): void
    {
        DB::transaction(function () use ($order, $paymentMethod, $verifiedByUserId) {
            // Kunci baris order ini agar tidak diproses 2 kali secara bersamaan
            $lockedOrder = QurbanOrder::where('id', $order->id)->lockForUpdate()->first();

            if ($lockedOrder->payment_status === 'success') {
                return; // Sudah pernah diproses sebelumnya, cegah duplikasi
            }

            $lockedOrder->update([
                'payment_status' => 'success',
                'payment_method' => $paymentMethod ?? $lockedOrder->payment_method,
                'paid_at' => now(),
                'invoice_number' => $lockedOrder->invoice_number ?? $this->generateInvoiceNumber(),
                'verified_by' => $verifiedByUserId,
                'verified_at' => $verifiedByUserId ? now() : null,
            ]);

            $this->refreshAnimalStatus($lockedOrder->sacrificial_animal_id);
        });
    }

    /**
     * Menghitung ulang status hewan berdasarkan jumlah pesanan sukses saat ini.
     */
    public function refreshAnimalStatus(int $sacrificialAnimalId): void
    {
        $animal = SacrificialAnimal::find($sacrificialAnimalId);

        if (! $animal) {
            return;
        }

        $successfulOrders = QurbanOrder::where('sacrificial_animal_id', $animal->id)
            ->where('payment_status', 'success')
            ->count();

        $newStatus = $successfulOrders >= $animal->max_participants ? 'fully_booked' : 'available';

        if ($animal->status !== 'slaughtered' && $animal->status !== $newStatus) {
            $animal->update(['status' => $newStatus]);
        }
    }

    /**
     * Melepas kembali booking yang sudah melewati batas waktu (reserved_until) tanpa pernah dibayar.
     * Dipanggil oleh Artisan Command terjadwal (Bab 11).
     */
    public function releaseExpiredBookings(): int
    {
        $expiredOrders = QurbanOrder::where('payment_status', 'pending')
            ->whereNotNull('reserved_until')
            ->where('reserved_until', '<', now())
            ->get();

        foreach ($expiredOrders as $order) {
            $order->update(['payment_status' => 'expired']);
        }

        return $expiredOrders->count();
    }

    public function generateInvoiceNumber(): string
    {
        return $this->generateSequentialNumber('invoice', 'KRB');
    }

    public function generateCertificateNumber(): string
    {
        return $this->generateSequentialNumber('certificate', 'SRT');
    }

    private function generateSequentialNumber(string $type, string $prefix): string
    {
        return DB::transaction(function () use ($type, $prefix) {
            $year = now()->year;

            $counter = QurbanInvoiceCounter::where('type', $type)->where('year', $year)->lockForUpdate()->first();

            if (! $counter) {
                $counter = QurbanInvoiceCounter::create(['type' => $type, 'year' => $year, 'last_number' => 0]);
                $counter = QurbanInvoiceCounter::where('id', $counter->id)->lockForUpdate()->first();
            }

            $nextNumber = $counter->last_number + 1;
            $counter->update(['last_number' => $nextNumber]);

            return sprintf('%s-%d-%05d', $prefix, $year, $nextNumber);
        });
    }
}
