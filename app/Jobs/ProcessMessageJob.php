<?php

namespace App\Jobs;

use App\Models\Message;
use App\Services\TelegramService;
use App\Supports\DefaultMessageProcessor;
use App\Supports\MessageProcessors\BotMessageProcessor;
use App\Supports\MessageProcessors\CartMessageProcessor;
use App\Supports\MessageProcessors\ContactUsMessageProcessor;
use App\Supports\MessageProcessors\RequestContactMessageProcessor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private Message $message) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        TelegramService::processMessage($this->message, [
            BotMessageProcessor::class,
            RequestContactMessageProcessor::class,
            CartMessageProcessor::class,
            ContactUsMessageProcessor::class,
        ], DefaultMessageProcessor::class);
    }
}
