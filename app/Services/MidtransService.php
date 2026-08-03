<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Membuat Snap Token untuk memulai transaksi pembayaran.
     *
     * @param  string  $orderId       ID transaksi unik (harus berbeda setiap transaksi)
     * @param  int     $amount        Nominal pembayaran dalam Rupiah (tanpa desimal)
     * @param  array   $customerDetails  Data pembeli: first_name, email, phone
     * @param  string  $itemName      Nama item yang tampil di halaman pembayaran Midtrans
     */
    public function createSnapToken(string $orderId, int $amount, array $customerDetails, string $itemName): string
    {
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $amount,
            ],
            'customer_details' => $customerDetails,
            'item_details' => [[
                'id' => $orderId,
                'price' => $amount,
                'quantity' => 1,
                'name' => $itemName,
            ]],
        ];

        return Snap::getSnapToken($params);
    }

    /**
     * Mengambil Snap Token yang sudah ada (jika belum kedaluwarsa) atau membuat yang baru.
     * Mencegah error "order_id has already been taken" saat halaman pembayaran
     * dibuka/refresh lebih dari sekali untuk transaksi Midtrans yang sama.
     */
    public function getOrCreateSnapToken(Model $model, string $orderId, int $amount, array $customerDetails, string $itemName): string
    {
        if ($model->snap_token && $model->snap_token_expires_at?->isFuture()) {
            return $model->snap_token;
        }

        $token = $this->createSnapToken($orderId, $amount, $customerDetails, $itemName);

        $model->forceFill([
            'snap_token' => $token,
            'snap_token_expires_at' => now()->addHours(23),
        ])->save();

        return $token;
    }

    /**
     * Mengambil status transaksi terkini langsung dari server Midtrans (bukan dari webhook).
     * Berguna sebagai fallback saat webhook belum/tidak sempat diterima — misalnya saat
     * development di localhost, di mana server Midtrans tidak bisa menjangkau URL notifikasi.
     * Mengembalikan null jika transaksi belum tercatat sama sekali di Midtrans (mis. popup Snap
     * ditutup sebelum bayar).
     */
    public function getPaymentStatus(string $orderId): ?string
    {
        try {
            $status = Transaction::status($orderId);
        } catch (\Exception) {
            return null;
        }

        return $this->mapTransactionStatus($status->transaction_status, $status->fraud_status ?? null);
    }

    /**
     * Memetakan status transaksi dari Midtrans ke status sistem internal.
     * Dipakai bersama oleh WebhookController (notifikasi async) dan getPaymentStatus() (cek sinkron).
     */
    public function mapTransactionStatus(string $transactionStatus, ?string $fraudStatus): string
    {
        return match ($transactionStatus) {
            'capture' => $fraudStatus === 'accept' ? 'success' : 'pending',
            'settlement' => 'success',
            'pending' => 'pending',
            'deny', 'cancel' => 'failed',
            'expire' => 'expired',
            default => 'pending',
        };
    }
}
