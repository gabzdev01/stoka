<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Reset demo tenant daily at midnight
Schedule::command('demo:reset')->dailyAt('00:00');

// Auto-close shifts that have been open for more than 24 hours
Schedule::command('shifts:close-stale')->daily();
