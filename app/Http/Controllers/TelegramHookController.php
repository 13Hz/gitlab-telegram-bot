<?php

namespace App\Http\Controllers;

use App\Factories\BuilderFactory;
use App\Models\Chat;
use App\Models\CreatedObject;
use App\Models\Json;
use App\Models\Link;
use App\Models\TelegramMessage;
use Illuminate\Http\Request;
use Longman\TelegramBot\Telegram;

define('TB_BASE_PATH', app_path());

class TelegramHookController extends Controller
{
    public function handle(Request $request)
    {
        $telegram = new Telegram(config('telegram.bot.token'), config('telegram.bot.name'));
        if ($request->header(config('telegram.gitlab.header')) === config('telegram.gitlab.token')) {
            $hookRequest = new \App\Models\Gitlab\Request(new Json($request->all()), $request->header('X-Gitlab-Instance'));
            $link = Link::where('link', '=', $hookRequest->project->web_url)->first();
            if ($link) {
                $builder = BuilderFactory::factory($hookRequest);

                $chats = $link->chats()->get();
                foreach ($chats as $chat) {
                    $ids[] = $chat->chat_id;

                    $data = [
                        'chat_id' => $chat->chat_id,
                        'text' => TelegramMessage::build($builder),
                        'parse_mode' => 'Markdown',
                        'disable_web_page_preview' => true
                    ];

                    if ($hookRequest->objectAttributes->iid) {
                        $createdObject = CreatedObject::where('object_id', $hookRequest->objectAttributes->iid)
                            ->where('chat_id', $chat->chat_id)->first();
                        if ($createdObject) {
                            $data['reply_to_message_id'] = $createdObject->message_id;
                        }
                    }

                    $response = \Longman\TelegramBot\Request::sendMessage($data);

                    if ($response->isOk() && $hookRequest->objectAttributes->action === 'open' && $hookRequest->objectAttributes->iid) {
                        CreatedObject::create([
                            'object_id' => $hookRequest->objectAttributes->iid,
                            'chat_id' => $chat->chat_id,
                            'message_id' => $response->getResult()->getMessageId()
                        ]);
                    }
                }

                return "Recipients: ".implode(', ', $ids);
            }
        } else {
            $telegram->enableAdmins(Chat::where('is_admin', true)->pluck('chat_id')->toArray());
            $telegram->setCommandsPath(app_path('Commands'))->handle();
        }
        return "ok";
    }
}
