<?php

namespace App\Console\Commands;

use App\Jobs\FetchMessagesJob;
use App\Models\Bot;
use Illuminate\Console\Command;

class FetchMessagesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:fetch-messages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Telegram Messages';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        foreach (Bot::all() as $bot) {
            FetchMessagesJob::dispatch($bot);
        }
    }
}
