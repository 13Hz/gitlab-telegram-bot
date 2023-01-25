<?php

namespace App\Http\Controllers;

use App\Models\Json;
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
            $hookRequest = new \App\Models\Gitlab\Request(new Json($request->all()));
            $link = Link::where('link', '=', $hookRequest->project->web_url)->first();
            if($link) {
                $text = "[{$hookRequest->project->path_with_namespace}]({$hookRequest->project->web_url})\n";
                $text .= "Пользователь [{$hookRequest->user->username}]({$request->header('X-Gitlab-Instance')}/{$hookRequest->user->username}) ";

                if($hookRequest->objectAttributes->action === 'merge') {
                    $text .= "слил изменения из ветки `{$hookRequest->objectAttributes->source_branch}` в `{$hookRequest->objectAttributes->target_branch}`\n";
                    $text .= "[Запрос на слияние №{$hookRequest->objectAttributes->iid}]({$hookRequest->objectAttributes->url})";
                }
                if($hookRequest->type === 'note') {
                    $text .= "оставил [комментарий]({$hookRequest->objectAttributes->url}) к {$hookRequest->objectAttributes->noteable_type}";
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
                        "issue" => "[issue #{$hookRequest->objectAttributes->iid}]({$hookRequest->objectAttributes->url})",
                        "merge_request" => "[merge request #{$hookRequest->objectAttributes->iid}]({$hookRequest->objectAttributes->url})",
                        default => "[object]({$hookRequest->objectAttributes->url})",
                    };
                }

                $chats = $link->chats()->get();
                foreach ($chats as $chat) {
                    $ids[] = $chat->chat_id;
                    \Longman\TelegramBot\Request::sendMessage([
                        'chat_id' => $chat->chat_id,
                        'text' => $text,
                        'parse_mode' => 'Markdown',
                        'disable_web_page_preview' => true
                    ]);
                }

                return "Recipients: ".implode(', ', $ids);
            }
        }
        else {
            $telegram->addCommandsPaths(config('telegram.commands.paths'));
            $telegram->handle();
        }
        return "ok";
    }
}
