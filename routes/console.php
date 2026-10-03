<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Runs once a day (01:00 UTC = 08:00 WIB) when the Laravel scheduler is running.
// On Railway the simpler route is a Cron service that runs `php artisan bookings:send-reminders` directly.
Schedule::command('bookings:send-reminders')->dailyAt('01:00');

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
