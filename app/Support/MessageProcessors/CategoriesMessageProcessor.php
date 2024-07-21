<?php

namespace App\Support\MessageProcessors;

use App\Enums\MessageProcessor\ReplyMarkupEnum;
use App\Services\ShopApi;
use App\Support\TelegramApi;
use App\Traits\MessageProcessorTrait;
use Illuminate\Support\Arr;

class CategoriesMessageProcessor extends MessageProcessor
{
    use MessageProcessorTrait;

    public function shouldProcess()
    {
        if ($this->message->message_text === ReplyMarkupEnum::CATEGORIES->value) {
            return true;
        }

        return false;
    }

    public function process()
    {
        $text = [];
        $commands = [];

        $jsonResponse = resolve(ShopApi::class)->json();

        $categories = Arr::get($jsonResponse, 'blog_categories.0.values', []);

        foreach ($categories as $categoryIndex => $category) {
            $text[] = '/'.static::PREFIX.($categoryIndex).' '.$category;
            $commands[static::PREFIX.($categoryIndex)] = $category;
        }

        (new TelegramApi($this->bot))->sendMessage(
            $this->message->chat_id,
            implode("\n\n", $text),
            $this->getDefaultReplyMarkup()
        );

        (new TelegramApi($this->bot))->setMyCommands(
            $commands,
            $this->getDefaultReplyMarkup()
        );
    }
}
