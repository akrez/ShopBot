<?php

namespace App\Supports\MessageProcessors;

use App\Enums\MessageProcessor\ReplyMarkupEnum;
use App\Services\ShopApi;
use App\Services\TelegramApiService;

class CategoriesMessageProcessor extends MessageProcessor
{
    public function shouldProcess()
    {
        if ($this->message->message_text === ReplyMarkupEnum::CATEGORIES->value) {
            return true;
        }

        return false;
    }

    public function process()
    {
        $commands = [];
        foreach (resolve(ShopApi::class)->categories() as $categoryIndex => $category) {
            $commands['category_'.($categoryIndex + 1)] = $category;
        }

        (new TelegramApiService($this->bot))->setMyCommands(
            $commands,
            $this->getDefaultReplyMarkup() + [
                'parse_mode' => 'HTML',
            ]
        );
    }
}
