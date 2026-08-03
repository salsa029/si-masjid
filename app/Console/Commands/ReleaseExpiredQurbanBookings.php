<?php

namespace App\Console\Commands;

use App\Services\QurbanService;
use Illuminate\Console\Command;

class ReleaseExpiredQurbanBookings extends Command
{
    protected $signature = 'qurban:release-expired-bookings';

    protected $description = 'Melepas kembali slot kurban yang dipesan namun tidak dibayar dalam batas waktu yang ditentukan.';

    public function handle(QurbanService $qurbanService): int
    {
        $releasedCount = $qurbanService->releaseExpiredBookings();

        $this->info("Berhasil melepas {$releasedCount} booking kurban yang kedaluwarsa.");

        return self::SUCCESS;
    }
}
