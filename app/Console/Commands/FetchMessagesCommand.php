<?php

namespace App\Console\Commands;

use App\Jobs\DispatchFetchMessagesJob;
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
        DispatchFetchMessagesJob::dispatch();
    }
}
