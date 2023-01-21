<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Longman\TelegramBot\Telegram;

class TelegramHookController extends Controller
{
    public function handle(Request $request) {
        $telegram = new Telegram(config('telegram.bot.token'), config('telegram.bot.name'));
        if($request->header(config('telegram.gitlab.header')) === config('telegram.gitlab.token'))
        {
            $hookRequest = new \App\Models\Gitlab\Request($request->all());
            $link = Link::where('link', '=', $hookRequest->project->web_url)->first();
            if($link) {
                $name = preg_replace("/(\W)/", '\\\$1', $hookRequest->project->path_with_namespace);
                $text = "[{$hookRequest->project->path_with_namespace}]({$hookRequest->project->web_url})\n";
                $text .= "Пользователь [{$hookRequest->user->username}](https://gitlab.com/{$hookRequest->user->username}) ";

                if($hookRequest->objectAttributes->action === 'merge') {
                    $text .= "слил изменения из ветки `{$hookRequest->objectAttributes->source_branch}` в `{$hookRequest->objectAttributes->target_branch}`\n";
                    $text .= "[Запрос на слияние №{$hookRequest->objectAttributes->iid}]({$hookRequest->objectAttributes->url})";
                }
                else {
                    $text .= match ($hookRequest->objectAttributes->action) {
                        "close" => "закрыл ",
                        "reopen" => "пересоздал ",
                        "update" => "изменил ",
                        "open" => "создал ",
                        default => "затронул ",
                    };

                    $text .= match ($hookRequest->type) {
                        "issue" => "[issue]({$hookRequest->objectAttributes->url}) [#{$hookRequest->objectAttributes->iid}]",
                        "merge_request" => "[merge request]({$hookRequest->objectAttributes->url}) [#{$hookRequest->objectAttributes->iid}]",
                        default => "[object]({$hookRequest->objectAttributes->url})",
                    };
                }

                $chats = $link->chats()->get();
                $ids = [];
                Log::info($text);
                foreach ($chats as $chat) {
                    $ids[] = $chat->chat_id;
                    $response = \Longman\TelegramBot\Request::sendMessage([
                        'chat_id' => $chat->chat_id,
                        'text' => $text,
                        'parse_mode' => 'Markdown',
                        'disable_web_page_preview' => true
                    ]);
                    Log::info($response);
                }

                return "Sended in: ".implode(', ', $ids);
            }
        }
        else {
            $telegram->addCommandsPaths([
                base_path('/app/Commands')
            ]);
            $telegram->handle();
        }
    }
}
