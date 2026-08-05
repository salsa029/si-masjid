<?php

namespace App\Console\Commands;

use App\Services\QurbanService;
use Illuminate\Console\Command;

class ProcessOverdueQurbanInstallments extends Command
{
    protected $signature = 'qurban:process-overdue-installments';

    protected $description = 'Melepas slot & mengalihkan dana cicilan kurban yang tidak lunas hingga batas waktu pelunasan.';

    public function handle(QurbanService $qurbanService): int
    {
        $result = $qurbanService->processOverdueInstallments();

        $this->info("Selesai: {$result['converted_to_infaq']} pesanan dana dialihkan ke infaq, {$result['flagged_for_refund']} pesanan ditandai untuk refund manual.");

        return self::SUCCESS;
    }
}
