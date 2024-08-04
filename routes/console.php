<?php

use App\Services\BotService;
use App\Services\MessageService;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    $botService = resolve(BotService::class);
    $messageService = resolve(MessageService::class);
    //
    foreach ($botService->getLatestApiBlogBots() as $bot) {
        foreach ($messageService->syncMessages($bot) as $message) {
            $result = $messageService->setMessageProcessor($bot, $message);
            $messageService->sendMessage($result->getData());
        }
    }
})->name('ScheduleCall')->withoutOverlapping(1)->everySecond();
