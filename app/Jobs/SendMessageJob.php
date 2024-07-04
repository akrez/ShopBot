<?php

namespace App\Jobs;

use App\Contracts\MessageProcessorContract;
use App\Services\TelegramService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private MessageProcessorContract $messageProcessor) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        TelegramService::sendMessage($this->messageProcessor);
    }
}
