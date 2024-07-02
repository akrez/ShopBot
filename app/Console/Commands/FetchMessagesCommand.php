<?php

namespace App\Console\Commands;

use App\Models\Message;
use App\Services\TelegramApiService;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

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
        $maxId = Message::max('id') + 0;
        $response = TelegramApiService::getUpdates($maxId + 1);
        $results = Arr::get($response, 'result', []);

        foreach ($results as $result) {

            $id = $result['update_id'];
            $content = ($result['message'] ?? $result['edited_message']);
            $messageText = ($content['text'] ?? null);

            $message = Message::create([
                'id' => $id,
                'chat_id' => $id,
                'content' => json_encode($content),
                'message_text' => $messageText,
            ]);

            if (
                isset($content['from']['is_bot']) and
                $content['from']['is_bot']
            ) {
                $message->delete();
            }
        }
    }
}
