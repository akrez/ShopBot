<?php

namespace App\Supports\MessageProcessors;

use App\Enums\MessageProcessor\ReplyMarkupEnum;
use App\Services\ShopApi;
use App\Services\TelegramApiService;

class CategoriesMessageProcessor extends MessageProcessor
{
    const PREFIX = 'category_';

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
        foreach (resolve(ShopApi::class)->categories() as $categoryIndex => $category) {
            $text[] = '/'.static::PREFIX.($categoryIndex + 1).' '.$category;
            $commands[static::PREFIX.($categoryIndex + 1)] = $category;
        }

        (new TelegramApiService($this->bot))->sendMessage(
            $this->message->chat_id,
            implode("\n", $text),
            $this->getDefaultReplyMarkup()
        );

        (new TelegramApiService($this->bot))->setMyCommands(
            $commands,
            $this->getDefaultReplyMarkup() + [
                'parse_mode' => 'HTML',
            ]
        );
    }
}
