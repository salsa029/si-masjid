<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

/**
 * Trait GeneratesSequentialNumbers
 *
 * Menghasilkan nomor urut (invoice, sertifikat, nomor transaksi, dst) yang terkunci
 * per tahun dan tipe, aman dari race condition antar request bersamaan.
 */
trait GeneratesSequentialNumbers
{
    private function generateSequentialNumber(string $counterModelClass, string $type, string $prefix): string
    {
        return DB::transaction(function () use ($counterModelClass, $type, $prefix) {
            $year = now()->year;

            $counter = $counterModelClass::where('type', $type)->where('year', $year)->lockForUpdate()->first();

            if (! $counter) {
                $counter = $counterModelClass::create(['type' => $type, 'year' => $year, 'last_number' => 0]);
                $counter = $counterModelClass::where('id', $counter->id)->lockForUpdate()->first();
            }

            $nextNumber = $counter->last_number + 1;
            $counter->update(['last_number' => $nextNumber]);

            return sprintf('%s-%d-%05d', $prefix, $year, $nextNumber);
        });
    }
}
