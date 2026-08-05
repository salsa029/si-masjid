<?php

namespace App\Services;

use App\Models\Infaq;
use App\Models\QurbanInstallment;
use App\Models\QurbanInvoiceCounter;
use App\Models\QurbanOrder;
use App\Models\SacrificialAnimal;
use App\Traits\GeneratesSequentialNumbers;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class QurbanService
{
    use GeneratesSequentialNumbers;

    public function __construct(protected DonationService $donationService) {}

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
     * Pesanan 'full' (beli sendirian) langsung menghabiskan seluruh kuota, terlepas
     * dari berapa max_participants-nya.
     */
    public function refreshAnimalStatus(int $sacrificialAnimalId): void
    {
        $animal = SacrificialAnimal::find($sacrificialAnimalId);

        if (! $animal) {
            return;
        }

        $hasFullOrder = QurbanOrder::where('sacrificial_animal_id', $animal->id)
            ->where('payment_status', 'success')
            ->where('order_type', 'full')
            ->exists();

        $patunganSlots = QurbanOrder::where('sacrificial_animal_id', $animal->id)
            ->where('payment_status', 'success')
            ->where('order_type', 'patungan')
            ->count();

        $newStatus = ($hasFullOrder || $patunganSlots >= $animal->max_participants) ? 'fully_booked' : 'available';

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

    /**
     * Membuat jadwal cicilan untuk sebuah pesanan kurban: cicilan pertama jatuh tempo hari ini
     * (uang muka), cicilan terakhir jatuh tempo pada batas waktu (biasanya akhir pendaftaran
     * Qurban Activity), dan cicilan di antaranya (jika ada) tersebar merata.
     */
    public function createInstallmentPlan(QurbanOrder $order, int $installmentCount, CarbonInterface $deadline): void
    {
        $baseAmount = floor($order->total_amount / $installmentCount);
        $today = now()->startOfDay();
        $totalDays = max(1, $today->diffInDays($deadline));

        for ($i = 0; $i < $installmentCount; $i++) {
            $amount = $baseAmount;
            if ($i === $installmentCount - 1) {
                // Sisa pembulatan dibebankan ke cicilan terakhir supaya totalnya pas.
                $amount = $order->total_amount - ($baseAmount * ($installmentCount - 1));
            }

            $dueDate = $i === 0
                ? $today->copy()
                : $today->copy()->addDays((int) round($totalDays * $i / ($installmentCount - 1)));

            QurbanInstallment::create([
                'qurban_order_id' => $order->id,
                'installment_number' => $i + 1,
                'amount' => $amount,
                'due_date' => $dueDate,
                'midtrans_order_id' => $order->midtrans_order_id . '-C' . ($i + 1),
            ]);
        }
    }

    /**
     * Menandai sebuah cicilan sebagai berhasil dibayar. Jika ini cicilan terakhir yang belum
     * lunas, pesanan induk otomatis ditandai lunas (sama seperti alur pembayaran penuh).
     *
     * Bersifat idempotent seperti markOrderAsSuccess().
     */
    public function markInstallmentAsSuccess(QurbanInstallment $installment, ?string $paymentMethod = null, ?int $verifiedByUserId = null): void
    {
        DB::transaction(function () use ($installment, $paymentMethod, $verifiedByUserId) {
            $lockedInstallment = QurbanInstallment::where('id', $installment->id)->lockForUpdate()->first();

            if ($lockedInstallment->payment_status === 'success') {
                return;
            }

            $lockedInstallment->update([
                'payment_status' => 'success',
                'paid_at' => now(),
                'verified_by' => $verifiedByUserId,
                'verified_at' => $verifiedByUserId ? now() : null,
            ]);

            $lockedOrder = QurbanOrder::where('id', $lockedInstallment->qurban_order_id)->lockForUpdate()->first();

            // Cicilan pertama lunas = uang muka aman, slot tidak lagi dilepas otomatis oleh
            // sweep 24 jam (releaseExpiredBookings) — selanjutnya batas waktu cicilan yang berlaku.
            if ($lockedInstallment->installment_number === 1) {
                $lockedOrder->update(['reserved_until' => null]);
            }

            $stillUnpaid = QurbanInstallment::where('qurban_order_id', $lockedOrder->id)
                ->where('payment_status', '!=', 'success')
                ->exists();

            if (! $stillUnpaid) {
                $lockedOrder->update([
                    'payment_status' => 'success',
                    'payment_method' => $paymentMethod ?? $lockedOrder->payment_method,
                    'paid_at' => now(),
                    'invoice_number' => $lockedOrder->invoice_number ?? $this->generateInvoiceNumber(),
                ]);

                $this->refreshAnimalStatus($lockedOrder->sacrificial_animal_id);
            }
        });
    }

    /**
     * Memproses pesanan cicilan yang sudah lewat batas waktu pelunasan tapi belum lunas.
     * Slot otomatis dilepas (payment_status jadi 'expired', sehingga tidak lagi terhitung
     * sebagai slot terpakai). Dana yang sudah terbayar otomatis dialihkan menjadi infaq,
     * KECUALI jamaah sudah mengajukan permintaan refund sebelum batas waktu — dalam hal ini
     * pesanan hanya ditandai untuk ditindaklanjuti admin secara manual.
     *
     * Dipanggil oleh Artisan Command terjadwal harian.
     */
    public function processOverdueInstallments(): array
    {
        $overdueOrders = QurbanOrder::where('payment_type', 'installment')
            ->where('payment_status', 'pending')
            ->whereNotNull('installment_deadline')
            ->where('installment_deadline', '<', now()->toDateString())
            ->get();

        $convertedToInfaq = 0;
        $flaggedForRefund = 0;

        foreach ($overdueOrders as $order) {
            DB::transaction(function () use ($order, &$convertedToInfaq, &$flaggedForRefund) {
                $lockedOrder = QurbanOrder::where('id', $order->id)->lockForUpdate()->first();

                if ($lockedOrder->payment_status !== 'pending') {
                    return; // Sudah diproses/berubah status sebelum sweep ini berjalan.
                }

                $paidAmount = (float) QurbanInstallment::where('qurban_order_id', $lockedOrder->id)
                    ->where('payment_status', 'success')
                    ->sum('amount');

                if ($lockedOrder->refund_requested) {
                    $lockedOrder->update([
                        'payment_status' => 'expired',
                        'verification_note' => 'Cicilan tidak lunas hingga batas waktu. Jamaah telah mengajukan permintaan refund untuk dana yang sudah dibayar (Rp '
                            . number_format($paidAmount, 0, ',', '.') . ') — mohon diproses manual oleh admin.',
                    ]);
                    $flaggedForRefund++;
                } else {
                    if ($paidAmount > 0) {
                        $infaq = Infaq::create([
                            'user_id' => $lockedOrder->user_id,
                            'amount' => $paidAmount,
                            'is_anonymous' => false,
                            'message' => 'Dana dialihkan otomatis dari cicilan kurban (pesanan #' . $lockedOrder->id . ') yang tidak lunas hingga batas waktu.',
                            'midtrans_order_id' => 'INF-AUTO-QRB' . $lockedOrder->id . '-' . Str::uuid(),
                            'payment_method' => 'manual_transfer',
                            'payment_status' => 'pending',
                        ]);
                        $this->donationService->markAsSuccess($infaq, 'manual_transfer');
                    }

                    $lockedOrder->update([
                        'payment_status' => 'expired',
                        'verification_note' => 'Cicilan tidak lunas hingga batas waktu. Dana yang sudah dibayar (Rp '
                            . number_format($paidAmount, 0, ',', '.') . ') telah dialihkan menjadi infaq.',
                    ]);
                    $convertedToInfaq++;
                }

                $this->refreshAnimalStatus($lockedOrder->sacrificial_animal_id);
            });
        }

        return ['converted_to_infaq' => $convertedToInfaq, 'flagged_for_refund' => $flaggedForRefund];
    }

    public function generateInvoiceNumber(): string
    {
        return $this->generateSequentialNumber(QurbanInvoiceCounter::class, 'invoice', 'KRB');
    }

    public function generateCertificateNumber(): string
    {
        return $this->generateSequentialNumber(QurbanInvoiceCounter::class, 'certificate', 'SRT');
    }
}
