<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

use App\Jobs\Membership\ExpireInvoiceJob;
use App\Jobs\Membership\ExpireMembershipJob;
use App\Jobs\Membership\ReminderPaymentJob;
use App\Jobs\Membership\ResetQuotaJob;

/*
|--------------------------------------------------------------------------
| Artisan Command
|--------------------------------------------------------------------------
*/

Artisan::command('inspire', function () {

    $this->comment(Inspiring::quote());

})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Membership Scheduler
|--------------------------------------------------------------------------
|
| Semua proses otomatis Membership CoreERP
|
*/

/*
|--------------------------------------------------------------------------
| Cek Invoice Expired
|--------------------------------------------------------------------------
|
| Setiap 5 menit
|
*/

Schedule::job(
    new ExpireInvoiceJob
)->everyFiveMinutes();

/*
|--------------------------------------------------------------------------
| Reminder Pembayaran
|--------------------------------------------------------------------------
|
| Setiap jam
|
*/

Schedule::job(
    new ReminderPaymentJob
)->hourly();

/*
|--------------------------------------------------------------------------
| Membership Expired
|--------------------------------------------------------------------------
|
| Setiap hari jam 00:10
|
*/

Schedule::job(
    new ExpireMembershipJob
)->dailyAt('00:10');

/*
|--------------------------------------------------------------------------
| Reset Quota Bulanan
|--------------------------------------------------------------------------
|
| Tanggal 1 jam 00:05
|
*/

Schedule::job(
    new ResetQuotaJob
)->monthlyOn(
    1,
    '00:05'
);