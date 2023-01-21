<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Telegram;

class webhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:webhook {url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
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
