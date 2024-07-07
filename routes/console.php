<?php

use App\Services\TelegramService;
use App\Supports\DefaultMessageProcessor;
use App\Supports\MessageProcessors\BotMessageProcessor;
use App\Supports\MessageProcessors\CartMessageProcessor;
use App\Supports\MessageProcessors\ContactUsMessageProcessor;
use App\Supports\MessageProcessors\RequestContactMessageProcessor;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    foreach (TelegramService::getBots() as $bot) {
        foreach (TelegramService::fetchMessages($bot) as $message) {
            $messageProcessor = TelegramService::processMessage($bot, $message, [
                BotMessageProcessor::class,
                RequestContactMessageProcessor::class,
                CartMessageProcessor::class,
                ContactUsMessageProcessor::class,
            ], DefaultMessageProcessor::class);
            //
            TelegramService::sendMessage($messageProcessor);
        }
    }
})->name('ScheduleCall')->withoutOverlapping()->everySecond();
