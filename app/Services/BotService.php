<?php

namespace App\Services;

use App\Contracts\PortContract;
use App\DTO\BotDTO;
use App\Models\Blog;
use App\Models\Bot;
use App\Support\ResponseBuilder;

class BotService implements PortContract
{
    public function getLatestBlogBotsQuery(Blog $blog)
    {
        return $blog->bots()->orderDefault('created_at');
    }

    public function store(Blog $blog, BotDTO $botDto)
    {
        $responseBuilder = resolve(ResponseBuilder::class)->input($botDto);

        $validation = $botDto->validate(true, [
            'blog' => $blog,
        ]);

        if ($validation->errors()->isNotEmpty()) {
            return $responseBuilder->status(422)->message('Unprocessable Entity')->errors($validation->errors());
        }

        $bot = $blog->bots()->create($botDto->data());

        if (! $bot) {
            return $responseBuilder->status(500)->message('Internal Server Error');
        }

        return $responseBuilder->status(201)->data($bot)->message(__(':name is created successfully', [
            'name' => __('Bot'),
        ]));
    }

    public function update(Blog $blog, Bot $bot, BotDTO $botDto)
    {
        $responseBuilder = resolve(ResponseBuilder::class)->input($botDto);

        $validation = $botDto->validate(false, [
            'blog' => $blog,
            'id' => $bot->id,
        ]);

        if ($validation->errors()->isNotEmpty()) {
            return $responseBuilder->status(422)->message('Unprocessable Entity')->errors($validation->errors());
        }

        $isSuccessful = $bot->update($botDto->data());

        if (! $isSuccessful) {
            return $responseBuilder->status(500)->message('Internal Server Error');
        }

        return $responseBuilder->status(200)->data($bot)->message(__(':name is updated successfully', [
            'name' => __('Bot'),
        ]));
    }

    public function findOrFailActiveBlogBot($botId)
    {
        $blog = resolve(BlogService::class)->findOrFailActiveBlog();
        $bot = $blog->bots()->where('id', $botId)->first();
        abort_unless($bot, 404);

        return $bot;
    }

    public function firstBotByCode(Blog $blog, ?string $code): ?Bot
    {
        if (strlen($code)) {
            return $blog->bots()->where('code', $code)->first();
        }

        return null;
    }

    public function exportToExcel(Blog $blog)
    {
        $source = [];

        $source[] = [
            __('validation.attributes.code'),
            __('validation.attributes.name'),
            __('validation.attributes.status'),
            __('validation.attributes.bot_order'),
        ];

        foreach ($this->getLatestBlogBotsQuery($blog)->get() as $bot) {
            $source[] = [
                $bot->code,
                $bot->name,
                $bot->bot_status->value,
                $bot->bot_order,
            ];
        }

        return $source;
    }

    public function importFromExcel(Blog $blog, array $rows)
    {
        $result = [];
        //
        $skipedRow = 0;
        foreach ($rows as $row) {
            if ($skipedRow < 1) {
                $skipedRow++;

                continue;
            }
            //
            $row = ((array) $row) + array_fill(0, 3, null);
            $botDTO = new BotDTO($row[0], $row[1], $row[2], $row[3]);
            //
            $bot = $this->firstBotByCode($blog, $botDTO->code);
            //
            if ($bot) {
                $result[] = $this->update($blog, $bot, $botDTO);
            } else {
                $result[] = $this->store($blog, $botDTO);
            }
        }

        return $result;
    }

    public function destroy(Blog $blog, Bot $bot)
    {
        if (! $bot->delete()) {
            return resolve(ResponseBuilder::class)->status(500)->message('Internal Server Error');
        }

        return resolve(ResponseBuilder::class)->status(200)->message(__(':name is deleted successfully', [
            'name' => __('Bot'),
        ]));
    }
}
