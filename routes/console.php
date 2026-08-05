<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('qurban:release-expired-bookings')->hourly();
Schedule::command('donation:release-expired-bookings')->hourly();
Schedule::command('qurban:process-overdue-installments')->daily();
