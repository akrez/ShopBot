<?php

use App\Services\MessageService;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    resolve(MessageService::class)->callSchedule();
})->name('ScheduleCall')->withoutOverlapping(1)->everySecond();
