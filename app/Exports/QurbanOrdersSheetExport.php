<?php

namespace App\Exports;

use App\Models\QurbanOrder;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class QurbanOrdersSheetExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(protected array $filters) {}

    public function query(): Builder
    {
        return QurbanOrder::query()
            ->with('animal')
            ->when($this->filters['status'], fn($query, $status) => $query->where('payment_status', $status))
            ->when($this->filters['date_from'], fn($query, $date) => $query->whereDate('paid_at', '>=', $date))
            ->when($this->filters['date_to'], fn($query, $date) => $query->whereDate('paid_at', '<=', $date))
            ->latest('paid_at');
    }

    public function headings(): array
    {
        return ['No. Transaksi', 'Pemesan', 'Hewan', 'Jenis Pesanan', 'Nominal (Rp)', 'Status', 'Tanggal Bayar'];
    }

    public function map($order): array
    {
        return [
            $order->midtrans_order_id,
            $order->user->name,
            $order->animal->name,
            $order->order_type === 'full' ? 'Penuh' : 'Patungan',
            $order->total_amount,
            ucfirst($order->payment_status),
            $order->paid_at?->format('d/m/Y H:i') ?? '-',
        ];
    }
}
