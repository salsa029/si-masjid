<?php

namespace App\Exports;

use App\Models\Infaq;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InfaqsSheetExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(protected array $filters) {}

    public function query(): Builder
    {
        return Infaq::query()
            ->with(['category', 'campaign'])
            ->when($this->filters['status'], fn($q, $s) => $q->where('payment_status', $s))
            ->when($this->filters['date_from'], fn($q, $d) => $q->whereDate('paid_at', '>=', $d))
            ->when($this->filters['date_to'], fn($q, $d) => $q->whereDate('paid_at', '<=', $d))
            ->latest('paid_at');
    }

    public function headings(): array
    {
        return ['No. Transaksi', 'Donatur', 'Kategori/Campaign', 'Nominal (Rp)', 'Metode Pembayaran', 'Status', 'Tanggal Bayar'];
    }

    public function map($infaq): array
    {
        return [
            $infaq->transaction_number,
            $infaq->display_name,
            $infaq->category->name ?? $infaq->campaign->title ?? 'Umum',
            $infaq->amount,
            $infaq->payment_method === 'midtrans' ? 'ONLINE' : 'TRANSFER MANUAL',
            ucfirst(str_replace('_', ' ', $infaq->payment_status)),
            $infaq->paid_at?->format('d/m/Y H:i') ?? '-',
        ];
    }
}
