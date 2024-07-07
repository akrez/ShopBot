<?php

use App\Services\TelegramService;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    foreach (TelegramService::getBots() as $bot) {
        foreach (TelegramService::fetchMessages($bot) as $message) {
            $messageProcessor = TelegramService::processMessage(
                $bot,
                $message,
                TelegramService::getMessageProcessorClasses(),
                TelegramService::getDefaultMessageProcessorClass()
            );
            //
            TelegramService::sendMessage($messageProcessor);
        }
    }
})->name('ScheduleCall')->withoutOverlapping(1)->everySecond();
