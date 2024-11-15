<?php

return [
    'bot' => [
        'token' => env('TELEGRAM_BOT_TOKEN', 'YOUR_TOKEN'),
        'name' => env('TELEGRAM_BOT_NAME', 'BOT_NAME')
    ],
    'gitlab' => [
        'header' => env('GITLAB_WEBHOOK_TOKEN_HEADER', 'x-gitlab-token'),
        'token' => env('GITLAB_WEBHOOK_TOKEN', 'YOUR_TOKEN'),
    ],
    'webhook' => [
        'header' => env('TELEGRAM_WEBHOOK_TOKEN_HEADER', 'X-Telegram-Bot-Api-Secret-Token'),
        'token' => env('TELEGRAM_WEBHOOK_TOKEN', hash('sha256', env('APP_KEY', '')))
    ],
];
