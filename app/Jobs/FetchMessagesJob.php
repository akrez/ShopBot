<?php

namespace App\Jobs;

use App\Models\Bot;
use App\Services\TelegramService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchMessagesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Bot $bot) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        (new TelegramService)->fetchMessages($this->bot);
    }
}
