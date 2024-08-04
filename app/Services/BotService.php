<?php

namespace App\Services;

use App\DTO\BotDTO;
use App\Models\Blog;
use App\Models\Bot;
use App\Support\ResponseBuilder;
use Illuminate\Database\Eloquent\Builder;

class BotService
{
    public function getLatestApiBlogBots()
    {
        return Bot::whereHas('blog', function (Builder $query) {
            $query->filterIsActive();
        })->orderDefault()->get();
    }

    public function getLatestBlogBotsQuery(Blog $blog)
    {
        return $blog->bots()->orderDefault();
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

    public function firstBotByToken(Blog $blog, ?string $token): ?Bot
    {
        if (strlen($token)) {
            return $blog->bots()->where('token', $token)->first();
        }

        return null;
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
