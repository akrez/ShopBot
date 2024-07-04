<?php

namespace App\Services;

use App\Contracts\MessageProcessorContract;
use App\Jobs\ProcessMessageJob;
use App\Jobs\SendMessageJob;
use App\Models\Message;
use App\Supports\DefaultMessageProcessor;
use App\Supports\MessageProcessors\BotMessageProcessor;
use App\Supports\MessageProcessors\CartMessageProcessor;
use App\Supports\MessageProcessors\ContactUsMessageProcessor;
use App\Supports\MessageProcessors\RequestContactMessageProcessor;
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
        $messageProcessor = static::detectMessageProcessor($message, [
            BotMessageProcessor::class,
            RequestContactMessageProcessor::class,
            CartMessageProcessor::class,
            ContactUsMessageProcessor::class,
        ]);

        $message->update([
            'response_type' => $messageProcessor->getResponseType(),
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
    protected static function detectMessageProcessor(Message $message, array $messageProcessorClasses): MessageProcessorContract
    {
        foreach ($messageProcessorClasses as $messageProcessorClass) {
            $messageProcessor = new $messageProcessorClass($message);
            if ($messageProcessor->getResponseType()) {
                return $messageProcessor;
            }
        }

        return static::getDefaultMessageProcessorInstance($message);
    }

    protected static function getDefaultMessageProcessorInstance(Message $message): MessageProcessorContract
    {
        return new DefaultMessageProcessor($message);
    }
}
