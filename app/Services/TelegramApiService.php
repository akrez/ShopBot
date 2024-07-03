<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TelegramApiService
{
    private static function getUrl($path)
    {
        return implode('/', [
            config('telegramapi.base_url'),
            config('telegramapi.token'),
            $path,
        ]);
    }

    private static function sendPostForm($path, $data = [], $headers = [])
    {
        $url = static::getUrl($path);

        return Http::withHeaders($headers)
            ->asForm()
            ->post($url, $data)
            ->json();
    }

    public static function getMe()
    {
        return static::sendPostForm('getMe');
    }

    public static function setMyCommands($commands, $optionalParameters = [])
    {
        $requiredParameters = [
            'commands' => [],
        ];

        foreach ($commands as $command => $description) {
            $requiredParameters['commands'][] = [
                'command' => $command,
                'description' => $description,
            ];
        }
        $requiredParameters['commands'] = json_encode($requiredParameters['commands']);

        return static::sendPostForm('setMyCommands', array_replace_recursive(
            $optionalParameters,
            $requiredParameters
        ));
    }

    public static function getUpdates($offset = null, $limit = 200)
    {
        return static::sendPostForm('getUpdates', [
            'limit' => $limit,
            'offset' => $offset,
        ]);
    }

    public static function sendMessage($chatId, $text, $optionalParameters = [])
    {
        $requiredParameters = [
            'chat_id' => $chatId,
            'text' => $text,
        ];

        return static::sendPostForm('sendMessage', array_replace_recursive(
            $optionalParameters,
            $requiredParameters
        ));
    }
}
