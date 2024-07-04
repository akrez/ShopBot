<?php

namespace App\Services;

use App\Contracts\MessageProcessorContract;
use App\Jobs\ProcessMessageJob;
use App\Jobs\SendMessageJob;
use App\Models\Message;
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

    /**
     * @param  array<int, MessageProcessorContract>  $messageProcessorClasses
     */
    public static function processMessage(Message $message, array $messageProcessorClasses, string $defaultMessageProcessorClass): void
    {
        $messageProcessor = static::detectMessageProcessor(
            $message,
            $messageProcessorClasses,
            $defaultMessageProcessorClass
        );

        $message->update([
            'processor' => $messageProcessor::class,
        ]);

        SendMessageJob::dispatch($messageProcessor);
    }

    public static function sendMessage(MessageProcessorContract $messageProcessor)
    {
        $messageProcessor->sendResponse();
    }

    /**
     * @param  array<int, MessageProcessorContract>  $messageProcessorClasses
     */
    protected static function detectMessageProcessor(Message $message, array $messageProcessorClasses, string $defaultMessageProcessorClass): MessageProcessorContract
    {
        foreach ($messageProcessorClasses as $messageProcessorClass) {
            $messageProcessor = new $messageProcessorClass($message);
            if ($messageProcessor->isProcessor()) {
                return $messageProcessor;
            }
        }

        return new $defaultMessageProcessorClass($message);
    }
}
