<?php

namespace App\Services;

use App\Contracts\MessageProcessorContract;
use App\DTO\MessageDTO;
use App\Models\Bot;
use App\Models\Message;
use App\Support\MessageProcessors\BotMessageProcessor;
use App\Support\MessageProcessors\CartMessageProcessor;
use App\Support\MessageProcessors\CategoriesMessageProcessor;
use App\Support\MessageProcessors\CategoryMessageProcessor;
use App\Support\MessageProcessors\ContactUsMessageProcessor;
use App\Support\MessageProcessors\RequestContactMessageProcessor;
use App\Support\MessageProcessors\SearchMessageProcessor;
use App\Support\ResponseBuilder;
use App\Support\TelegramApi;
use Illuminate\Support\Arr;

class MessageService
{
    /**
     * @return array<int, MessageProcessorContract>
     */
    public function getMessageProcessorClasses(): array
    {
        return [
            BotMessageProcessor::class,
            RequestContactMessageProcessor::class,
            CategoryMessageProcessor::class,
            CategoriesMessageProcessor::class,
            CartMessageProcessor::class,
            ContactUsMessageProcessor::class,
        ];
    }

    public function getDefaultMessageProcessorClass(): string
    {
        return SearchMessageProcessor::class;
    }

    public function detectMessageProcessor(Bot $bot, Message $message): MessageProcessorContract
    {
        foreach ($this->getMessageProcessorClasses() as $messageProcessorClass) {
            $messageProcessor = new $messageProcessorClass($bot, $message);
            if ($messageProcessor->shouldProcess()) {
                return $messageProcessor;
            }
        }

        $defaultMessageProcessorClass = $this->getDefaultMessageProcessorClass();

        return new $defaultMessageProcessorClass($bot, $message);
    }

    /**
     * @return array<int, Message>
     */
    public function syncMessages(Bot $bot): array
    {
        $messages = [];
        //
        $maxId = $bot->messages()->max('id');
        $response = (new TelegramApi($bot))->getUpdates($maxId + 1);
        //
        foreach (Arr::get($response, 'result', []) as $result) {
            $content = ($result['message'] ?? $result['edited_message']);
            //
            $response = $this->store($bot, new MessageDTO(
                $result['update_id'],
                $bot->id,
                ($content['chat']['id'] ?? null),
                ($content['text'] ?? null),
                json_encode($content)
            ));
            //
            if ($response->isSuccessful()) {
                $messages[] = $response->getData();
            }
        }

        return $messages;
    }

    public function setMessageProcessor(Bot $bot, Message $message)
    {
        $messageProcessor = $this->detectMessageProcessor($bot, $message);

        $responseBuilder = resolve(ResponseBuilder::class)->data($messageProcessor);

        $isSuccessful = $message->update([
            'processor' => $messageProcessor::class,
        ]);

        if (! $isSuccessful) {
            return $responseBuilder->status(500)->message('Internal Server Error');
        }

        return $responseBuilder->status(200)->message(__(':name is updated successfully', [
            'name' => __('Message'),
        ]));
    }

    public function sendMessage(MessageProcessorContract $messageProcessor)
    {
        $messageProcessor->process();
    }

    public function store(Bot $bot, MessageDTO $messageDTO)
    {
        $responseBuilder = resolve(ResponseBuilder::class)->input($messageDTO);

        $validation = $messageDTO->validate(true);

        if ($validation->errors()->isNotEmpty()) {
            return $responseBuilder->status(422)->message('Unprocessable Entity')->errors($validation->errors());
        }

        $message = $bot->messages()->create($messageDTO->data());

        if (! $message) {
            return $responseBuilder->status(500)->message('Internal Server Error');
        }

        return $responseBuilder->status(201)->data($message)->message(__(':name is created successfully', [
            'name' => __('Message'),
        ]));
    }
}
