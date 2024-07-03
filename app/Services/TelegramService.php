<?php

namespace App\Services;

use App\Contracts\MessageProcessorContract;
use App\Jobs\ProcessMessageJob;
use App\Jobs\SendMessageJob;
use App\Models\Message;
use App\Supports\MessageProcessor\BotMessageProcessor;
use App\Supports\MessageProcessor\NoneMessageProcessor;
use Illuminate\Support\Arr;

class TelegramService
{
    public static function fetchMessages()
    {
        $maxId = Message::max('id');
        $response = TelegramApiService::getUpdates($maxId + 1);
        $results = Arr::get($response, 'result', []);
        //
        foreach ($results as $result) {
            $id = $result['update_id'];
            $content = ($result['message'] ?? $result['edited_message']);
            //
            $message = Message::create([
                'id' => $id,
                'chat_id' => ($content['chat']['id'] ?? null),
                'message_text' => ($content['text'] ?? null),
                'message_json' => json_encode($content),
            ]);
            //
            ProcessMessageJob::dispatch($message);
        }
    }

    public static function processMessage(Message $message)
    {
        $messageProcessorClasses = [
            BotMessageProcessor::class,
        ];

        foreach ($messageProcessorClasses as $messageProcessorClass) {
            $messageProcessor = new $messageProcessorClass($message);
            $responseType = $messageProcessor->getResponseType();
            if ($responseType) {
                $message->update([
                    'response_type' => $responseType,
                ]);
                SendMessageJob::dispatch($messageProcessor);

                return;
            }
        }

        SendMessageJob::dispatch(new NoneMessageProcessor($message));
    }

    public static function sendMessage(MessageProcessorContract $messageProcessor)
    {
        $messageProcessor->sendResponse();
    }
}
