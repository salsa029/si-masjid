<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class FinancialReportExport implements WithMultipleSheets
{
    public function __construct(protected array $filters) {}

    public function sheets(): array
    {
        return [
            'Infaq' => new InfaqsSheetExport($this->filters),
            'Zakat' => new ZakatsSheetExport($this->filters),
            'Pesanan Kurban' => new QurbanOrdersSheetExport($this->filters), // tetap
        ];
    }
}
