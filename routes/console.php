<?php

use App\Services\BotService;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    foreach ((new BotService)->getLatestBotsQuery() as $bot) {
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
