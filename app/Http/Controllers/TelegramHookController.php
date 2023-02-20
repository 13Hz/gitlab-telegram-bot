<?php

namespace App\Http\Controllers;

use App\Factories\BuilderFactory;
use App\Models\Chat;
use App\Models\ChatLink;
use App\Models\Core\Json;
use App\Models\Core\TelegramMessage;
use App\Models\CreatedObject;
use App\Models\Link;
use App\Models\Trigger;
use App\Services\ChatLinkService;
use App\Services\TriggerFilterService;
use Illuminate\Http\Request;
use Longman\TelegramBot\Telegram;

define('TB_BASE_PATH', app_path());

class TelegramHookController extends Controller
{
    public function handle(Request $request, ChatLinkService $chatLinkService, TriggerFilterService $triggerFilterService)
    {
        $telegram = new Telegram(config('telegram.bot.token'), config('telegram.bot.name'));
        if ($request->header(config('telegram.gitlab.header')) === config('telegram.gitlab.token')) {
            $hookRequest = new \App\Models\Gitlab\Request(new Json($request->all()), $request->header('X-Gitlab-Instance'));
            $link = Link::where('link', '=', $hookRequest->project->web_url)->first();
            if ($link) {
                $builder = BuilderFactory::factory($hookRequest);
                $text = TelegramMessage::build($builder);

                $chats = $link->chats()->get();
                foreach ($chats as $chat) {
                    $chatLink = $chatLinkService->getChatLinkByEntitiesId($chat, $link);
                    $trigger = Trigger::where('code', $hookRequest->type)->first();
                    if (($chatLink && $trigger) && !$triggerFilterService->getTriggerState($chatLink, $trigger)) {
                        continue;
                    }

                    $ids[] = $chat->chat_id;

                    $data = [
                        'chat_id' => $chat->chat_id,
                        'text' => $text,
                        'parse_mode' => 'Markdown',
                        'disable_web_page_preview' => true
                    ];

                    if ($hookRequest->objectAttributes->iid && $hookRequest->type) {
                        $createdObject = CreatedObject::where('object_id', $hookRequest->objectAttributes->iid)
                            ->where('chat_id', $chat->chat_id)
                            ->where('object_type', $hookRequest->type)
                            ->first();
                        if ($createdObject) {
                            $data['reply_to_message_id'] = $createdObject->message_id;
                        }
                    }

                    $response = \Longman\TelegramBot\Request::sendMessage($data);

                    if ($response->isOk()
                        && $hookRequest->objectAttributes->action === 'open'
                        && $hookRequest->objectAttributes->iid
                        && $hookRequest->type) {
                        CreatedObject::create([
                            'object_id' => $hookRequest->objectAttributes->iid,
                            'chat_id' => $chat->chat_id,
                            'message_id' => $response->getResult()->getMessageId(),
                            'object_type' => $hookRequest->type
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
