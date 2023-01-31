<?php

namespace App\Http\Controllers;

use App\Factories\BuilderFactory;
use App\Models\Chat;
use App\Models\Json;
use App\Models\Link;
use App\Models\TelegramMessage;
use Illuminate\Http\Request;
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

                $builder = BuilderFactory::factory($hookRequest);

                $chats = $link->chats()->get();
                foreach ($chats as $chat) {
                    $ids[] = $chat->chat_id;
                    \Longman\TelegramBot\Request::sendMessage([
                        'chat_id' => $chat->chat_id,
                        'text' => TelegramMessage::build($builder),
                        'parse_mode' => 'Markdown',
                        'disable_web_page_preview' => true
                    ]);
                }

                return "Recipients: ".implode(', ', $ids);
            }
        }
        else {
            $telegram->enableAdmins(Chat::where('is_admin', true)->pluck('chat_id')->toArray());
            $telegram->setCommandsPaths([app_path('/Commands')]);
            $telegram->handle();
        }
        return "ok";
    }
}
