<?php

use App\Jobs\ProcessRecurringTransactionsJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::job(new ProcessRecurringTransactionsJob, 'default', 'rabbitmq')->dailyAt('00:00');
