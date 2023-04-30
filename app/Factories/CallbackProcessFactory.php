<?php

namespace App\Factories;

use App\Models\Core\CallbackProcess;
use App\Models\Core\DefaultProcess;
use App\Models\Core\FilterPressedProcess;
use App\Models\Core\LinkPressedProcess;
use App\Services\ChatLinkService;
use App\Services\ChatService;
use App\Services\LinkService;
use Longman\TelegramBot\Entities\CallbackQuery;

class CallbackProcessFactory
{
    /**
     * @param CallbackQuery $query
     * @return CallbackProcess
     */
    public static function factory(CallbackQuery $query): CallbackProcess
    {
        $chatId = $query->getMessage()->getChat()->getId();
        $data = json_decode($query->getData(), true);

        $chatService = new ChatService();
        $linkService = new LinkService();
        $chatLinkService = new ChatLinkService();

        $chat = $chatService->getChatByChatId($chatId);
        $link = $linkService->getLinkById($data['id']);
        if ($chat && $link) {
            $chatLink = $chatLinkService->getChatLinkByEntitiesId($chat, $link);
            if ($chatLink) {
                return match ($data['entity']) {
                    'link' => new LinkPressedProcess($query, $chat, $link, $chatLink),
                    'filter' => new FilterPressedProcess($query, $chat, $link, $chatLink),
                };
            }
        }

        return new DefaultProcess($query);
    }
}
