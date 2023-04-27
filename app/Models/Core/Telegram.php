<?php

namespace App\Models\Core;

use Longman\TelegramBot\Entities\ServerResponse;

define('TB_BASE_PATH', app_path());

class Telegram
{
    private static \Longman\TelegramBot\Telegram|null $instance = null;

    public static function getInstance(): \Longman\TelegramBot\Telegram
    {
        if (!self::$instance) {
            self::$instance = new \Longman\TelegramBot\Telegram(config('telegram.bot.token'), config('telegram.bot.name'));
        }

        return self::$instance;
    }

    public static function sendMessage($data): ServerResponse
    {
        \Longman\TelegramBot\Request::initialize(\App\Models\Core\Telegram::getInstance());
        return \Longman\TelegramBot\Request::sendMessage($data);
    }
}
