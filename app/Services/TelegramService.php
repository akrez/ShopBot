<?php

namespace App\Services;

use App\Contracts\MessageProcessorContract;
use App\Jobs\ProcessMessageJob;
use App\Jobs\SendMessageJob;
use App\Models\Bot;
use App\Models\Message;
use Illuminate\Support\Arr;

class TelegramService
{
    public static function fetchMessagesJob(Bot $bot)
    {
        foreach (static::fetchMessages($bot) as $message) {
            ProcessMessageJob::dispatch($bot, $message);
        }
    }

    /**
     * @return array<int, Message>
     */
    public static function fetchMessages(Bot $bot): array
    {
        $messages = [];
        //
        $maxId = Message::where('bot_id', $bot->id)->max('id');
        $response = (new TelegramApiService($bot))->getUpdates($maxId + 1);
        $results = Arr::get($response, 'result', []);
        //
        foreach ($results as $result) {
            $id = $result['update_id'];
            $content = ($result['message'] ?? $result['edited_message']);
            //
            $message = Message::create([
                'id' => $id,
                'bot_id' => $bot->id,
                'chat_id' => ($content['chat']['id'] ?? null),
                'message_text' => ($content['text'] ?? null),
                'message_json' => json_encode($content),
            ]);
            //
            $messages[] = $message;
        }

        return $messages;
    }

    /**
     * @param  array<int, string>  $messageProcessorClasses
     */
    public static function processMessageJob(
        Bot $bot,
        Message $message,
        array $messageProcessorClasses,
        string $defaultMessageProcessorClass
    ): void {
        $messageProcessor = static::processMessage(
            $bot,
            $message,
            $messageProcessorClasses,
            $defaultMessageProcessorClass
        );
        SendMessageJob::dispatch($messageProcessor);
    }

    /**
     * @param  array<int, MessageProcessorContract>  $messageProcessorClasses
     */
    public static function processMessage(
        Bot $bot,
        Message $message,
        array $messageProcessorClasses,
        string $defaultMessageProcessorClass
    ): MessageProcessorContract {
        $messageProcessor = static::detectMessageProcessor(
            $bot,
            $message,
            $messageProcessorClasses,
            $defaultMessageProcessorClass
        );

        $message->update([
            'processor' => $messageProcessor::class,
        ]);

        return $messageProcessor;
    }

    public static function sendMessage(MessageProcessorContract $messageProcessor)
    {
        $messageProcessor->process();
    }

    /**
     * @param  array<int, MessageProcessorContract>  $messageProcessorClasses
     */
    protected static function detectMessageProcessor(
        Bot $bot,
        Message $message,
        array $messageProcessorClasses,
        string $defaultMessageProcessorClass
    ): MessageProcessorContract {
        foreach ($messageProcessorClasses as $messageProcessorClass) {
            $messageProcessor = new $messageProcessorClass($bot, $message);
            if ($messageProcessor->shouldProcess()) {
                return $messageProcessor;
            }
        }

        return new $defaultMessageProcessorClass($bot, $message);
    }
}
