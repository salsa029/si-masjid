<?php

namespace App\Exports;

use App\Models\Zakat;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ZakatsSheetExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(protected array $filters) {}

    public function query(): Builder
    {
        return Zakat::query()
            ->with('zakatType')
            ->when($this->filters['status'], fn($q, $s) => $q->where('payment_status', $s))
            ->when($this->filters['date_from'], fn($q, $d) => $q->whereDate('paid_at', '>=', $d))
            ->when($this->filters['date_to'], fn($q, $d) => $q->whereDate('paid_at', '<=', $d))
            ->latest('paid_at');
    }

    public function headings(): array
    {
        return ['No. Transaksi', 'Muzakki', 'Jenis Zakat', 'Nominal (Rp)', 'Metode Pembayaran', 'Status', 'Tanggal Bayar'];
    }

    public function map($zakat): array
    {
        return [
            $zakat->transaction_number,
            $zakat->display_name,
            $zakat->zakatType->name,
            $zakat->amount,
            $zakat->payment_method === 'midtrans' ? 'ONLINE' : 'TRANSFER MANUAL',
            ucfirst(str_replace('_', ' ', $zakat->payment_status)),
            $zakat->paid_at?->format('d/m/Y H:i') ?? '-',
        ];
    }
}
