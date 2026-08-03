<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;

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
}
