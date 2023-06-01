<?php

namespace App\Services;

use App\Builders\NoteBuilder;
use App\Models\Comment;
use App\Models\Core\Telegram;
use App\Models\CreatedObject;
use App\Models\Gitlab\Request;
use App\Models\Link;
use App\Models\Trigger;
use Carbon\Carbon;

class GitlabRepositoryService
{
    private Request $hookRequest;

    const OBJECT_STATUS_OPEN = 'open';
    const NOTE_TYPE = 'note';

    public function __construct(Request $hookRequest)
    {
        $this->hookRequest = $hookRequest;
    }

    /**Получить модель связки репозитория по URL
     * @param mixed $url
     * @return Link|null
     */
    public function getLinkByUrl(mixed $url): Link|null
    {
        if (!empty($url)) {
            return Link::where('link', '=', $url)->first();
        }

        return null;
    }

    /**Получить список доступных чатов для отправки уведомлений (если триггера нет в системе, считается, что чат доступен для отправки)
     * @param Link $link
     * @param $type
     * @return array App\Models\Chat
     */
    public function getAvailableChatsByTriggerFilter(Link $link, $type): array
    {
        $result = [];
        $triggerFilterService = new TriggerFilterService();
        $chatLinkService = new ChatLinkService();
        $trigger = Trigger::where('code', $type)->first();
        foreach ($link->chats()->get() as $chat) {
            $chatLink = $chatLinkService->getChatLinkByEntitiesId($chat, $link);
            if (($chatLink && $trigger) && !$triggerFilterService->getTriggerState($chatLink, $trigger)) {
                continue;
            }

            $result[] = $chat;
        }

        return $result;
    }

    /**Собрать тело для отправки сообщения через апи Telegram
     * @param mixed $chatId
     * @param string $text
     * @param mixed|null $replyMessageId
     * @return array
     */
    public function getTelegramMessageData(mixed $chatId, string $text, mixed $replyMessageId = null): array
    {
        $data = [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'Markdown',
            'disable_web_page_preview' => true
        ];

        if (!empty($replyMessageId)) {
            $data['reply_to_message_id'] = $replyMessageId;
        }

        return $data;
    }

    public function getTelegramEditMessageData(mixed $chatId, string $text, mixed $messageId): array
    {
        return [
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'text' => $text,
            'parse_mode' => 'Markdown',
            'disable_web_page_preview' => true
        ];
    }

    /**Отправка сообщений в чаты, связанные с репозиторием
     * @param Link $link Репозиторий
     * @param string $text Текст сообщения
     * @return array Идентификаторы чатов, в которые отправлены сообщения
     */
    public function sendMessageToChats(Link $link, string $text): array
    {
        $ids = [];
        $chats = $this->getAvailableChatsByTriggerFilter($link, $this->hookRequest->type);

        foreach ($chats as $chat) {
            if ($this->hookRequest->type === self::NOTE_TYPE) {
                $mainComment = Comment::where([
                    'author_id' => $this->hookRequest->user->id,
                    'object_id' => $this->hookRequest->iid,
                    'chat_id' => $chat->chat_id,
                    'reference' => null
                ])->where('updated_at', '>', Carbon::now()->subMinute())->first();

                if ($mainComment) {
                    Comment::create([
                        'author_id' => $this->hookRequest->user->id,
                        'object_id' => $this->hookRequest->iid,
                        'chat_id' => $chat->chat_id,
                        'message_id' => $mainComment->message_id,
                        'reference' => $mainComment->id
                    ]);
                    $referencedCount = Comment::where([
                        'reference' => $mainComment->id
                    ])->count();

                    $builder = new NoteBuilder($this->hookRequest);
                    $builder->addRepositoryLink();
                    $builder->addUserActionTextMultipleMessages($referencedCount + 1);

                    Telegram::editMessage(
                        $this->getTelegramEditMessageData($chat->chat_id, $builder->getText(), $mainComment->message_id)
                    );

                    continue;
                }
            }

            $trigger = Trigger::where('code', $this->hookRequest->type)->first();
            $replyMessageId = null;

            if ($this->hookRequest->objectAttributes->iid && $this->hookRequest->type && $trigger) {
                $createdObject = CreatedObject::where('object_id', $this->hookRequest->objectAttributes->iid)
                    ->where('chat_id', $chat->id)
                    ->where('trigger_id', $trigger->id)
                    ->first();
                if ($createdObject) {
                    $replyMessageId = $createdObject->message_id;
                }
            }

            $response = Telegram::sendMessage(
                $this->getTelegramMessageData($chat->chat_id, $text, $replyMessageId)
            );

            if ($response->isOk()) {
                $ids[] = $chat->chat_id;
                $messageId = $response->getResult()->getMessageId();

                if ($this->hookRequest->objectAttributes->action === self::OBJECT_STATUS_OPEN
                    && $this->hookRequest->objectAttributes->iid
                    && $this->hookRequest->type) {
                    CreatedObject::create([
                        'object_id' => $this->hookRequest->objectAttributes->iid,
                        'chat_id' => $chat->id,
                        'message_id' => $messageId,
                        'trigger_id' => $trigger->id
                    ]);
                }

                if ($this->hookRequest->type === self::NOTE_TYPE) {
                    Comment::create([
                        'object_id' => $this->hookRequest->iid,
                        'author_id' => $this->hookRequest->user->id,
                        'chat_id' => $chat->chat_id,
                        'message_id' => $messageId
                    ]);
                }
            }
        }

        return $ids;
    }
}
