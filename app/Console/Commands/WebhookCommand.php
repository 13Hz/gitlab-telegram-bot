<?php

namespace App\Console\Commands;

use App\Models\Core\Telegram;
use Illuminate\Console\Command;
use Longman\TelegramBot\Exception\TelegramException;
use Symfony\Component\Console\Command\Command as CommandAlias;

class WebhookCommand extends Command
{
    protected $signature = 'telegram:webhook';
    protected $description = 'Установка ссылки на webhook для телеграм бота';

    public function handle()
    {
        try {

            $webhookUrl = $this->ask('По какому адресу находится Webhook?', url('/hook'));
            $telegram = Telegram::getInstance();
            $result = $telegram->setWebhook($webhookUrl, [
                'secret_token' => config('telegram.webhook.token')
            ]);

            if ($result->isOk()) {
                $this->info($result->getDescription());

                return CommandAlias::SUCCESS;
            } else {
                $this->error($result->getDescription());
            }
        } catch (TelegramException $e) {
            $this->error($e->getMessage());
        }

        return CommandAlias::FAILURE;
    }
}
