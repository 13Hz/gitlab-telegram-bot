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
    'commands' => [
        'paths' => [
            env('TELEGRAM_BOT_COMMANDS_PATH')
        ]
    ]
];
