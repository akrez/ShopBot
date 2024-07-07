<?php

namespace App\Jobs;

use App\Models\Bot;
use App\Models\Message;
use App\Services\TelegramService;
use App\Supports\DefaultMessageProcessor;
use App\Supports\MessageProcessors\BotMessageProcessor;
use App\Supports\MessageProcessors\CartMessageProcessor;
use App\Supports\MessageProcessors\ContactUsMessageProcessor;
use App\Supports\MessageProcessors\RequestContactMessageProcessor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;

class ProcessMessageJob implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private Bot $bot,
        private Message $message
    ) {
        $this->onQueue('ProcessMessageJob');
    }

    /**
     * The number of seconds after which the job's unique lock will be released.
     *
     * @var int
     */
    public $uniqueFor = 60;

    /**
     * Get the unique ID for the job.
     */
    public function uniqueId(): string
    {
        return $this->bot->id.'-'.$this->message->id;
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array<int, object>
     */
    public function middleware(): array
    {
        return [
            new WithoutOverlapping($this->bot->id.'-'.$this->message->id),
        ];
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        TelegramService::processMessageJob($this->bot, $this->message, [
            BotMessageProcessor::class,
            RequestContactMessageProcessor::class,
            CartMessageProcessor::class,
            ContactUsMessageProcessor::class,
        ], DefaultMessageProcessor::class);
    }
}
