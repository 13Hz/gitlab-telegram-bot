<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Telegram;

class WebhookCommand extends Command
{
    protected $signature = 'telegram:webhook {url}';
    protected $description = 'Установка ссылки на webhook для телеграм бота';

    public function handle()
    {
        try {
            $telegram = new Telegram(env('TELEGRAM_BOT_TOKEN'), env('TELEGRAM_BOT_NAME'));

            $result = $telegram->setWebhook($this->argument('url'));
            if ($result->isOk()) {
                $this->info($result->getDescription());
                return Command::SUCCESS;
            }
        } catch (TelegramException $e) {
            $this->error($e->getMessage());
        }

        return Command::FAILURE;
    }
}
