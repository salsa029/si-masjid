<?php

namespace App\Http\Controllers;

use App\Models\Infaq;
use App\Models\Zakat;
use App\Models\QurbanOrder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Midtrans\Notification;
use App\Services\DonationService;
use App\Services\MidtransService;
use App\Services\QurbanService;

class WebhookController extends Controller
{
    public function __construct(
        protected QurbanService $qurbanService,
        protected DonationService $donationService,
        protected MidtransService $midtransService,
    ) {}

    public function handle(): JsonResponse
    {
        $notification = new Notification();

        $orderId = $notification->order_id;
        $transactionStatus = $notification->transaction_status;
        $fraudStatus = $notification->fraud_status ?? null;

        Log::info('Midtrans Webhook diterima', [
            'order_id' => $orderId,
            'transaction_status' => $transactionStatus,
            'fraud_status' => $fraudStatus,
        ]);

        $paymentStatus = $this->midtransService->mapTransactionStatus($transactionStatus, $fraudStatus);

        // Routing penanganan transaksi berdasarkan prefiks Order ID
        if (Str::startsWith($orderId, 'INF-')) {
            $this->handleInfaq($orderId, $paymentStatus);
        } elseif (Str::startsWith($orderId, 'ZKT-')) {
            $this->handleZakat($orderId, $paymentStatus);
        } elseif (Str::startsWith($orderId, 'QRB-')) {
            $this->handleQurbanOrder($orderId, $paymentStatus);
        }

        return response()->json(['message' => 'Notifikasi berhasil diproses.']);
    }

    /**
     * Menangani pembaruan status untuk transaksi Infaq.
     */
    private function handleInfaq(string $orderId, string $paymentStatus): void
    {
        $infaq = Infaq::where('midtrans_order_id', $orderId)->first();

        if (!$infaq) {
            Log::warning("Webhook: Infaq dengan order_id {$orderId} tidak ditemukan.");
            return;
        }

        if ($paymentStatus === 'success') {
            $this->donationService->markAsSuccess($infaq, 'midtrans');
            return;
        }

        $infaq->update(['payment_status' => $paymentStatus]);
    }

    /**
     * Menangani pembaruan status untuk transaksi Zakat.
     */
    private function handleZakat(string $orderId, string $paymentStatus): void
    {
        $zakat = Zakat::where('midtrans_order_id', $orderId)->first();

        if (!$zakat) {
            Log::warning("Webhook: Zakat dengan order_id {$orderId} tidak ditemukan.");
            return;
        }

        if ($paymentStatus === 'success') {
            $this->donationService->markAsSuccess($zakat, 'midtrans');
            return;
        }

        $zakat->update(['payment_status' => $paymentStatus]);
    }

    /**
     * Menangani pembaruan status untuk transaksi Qurban.
     */
    private function handleQurbanOrder(string $orderId, string $paymentStatus): void
    {
        $qurbanOrder = QurbanOrder::where('midtrans_order_id', $orderId)->first();

        if (!$qurbanOrder) {
            Log::warning("Webhook: QurbanOrder dengan order_id {$orderId} tidak ditemukan.");
            return;
        }

        if ($paymentStatus === 'success') {
            $this->qurbanService->markOrderAsSuccess($qurbanOrder, 'midtrans');
            return;
        }

        $qurbanOrder->update(['payment_status' => $paymentStatus]);
    }
}
