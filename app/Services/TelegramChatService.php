<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\ChatLink;
use App\Models\Core\ResultContainer;
use App\Models\Link;
use App\Traits\ParserTraits;

class TelegramChatService
{
    use ParserTraits;

    public function createChat(string|int $chatId, string $chatType): ResultContainer
    {
        $result = new ResultContainer();
        $chatService = new CharService();

        if ($chatService->isChatExists($chatId)) {
            $result->setSuccess(false)->setMessage('Чат уже присутствует в базе');
        } else {
            $model = $chatService->createChat($chatId, $chatType);

            if ($model instanceof Chat) {
                $result->setSuccess(true)
                    ->addLine('Чат успешно зарегистрирован')
                    ->addLine('Введите /help для получения списка доступных команд');
            } else {
                $result->setSuccess(false)->setMessage('Произошла ошибка при добавлении чата в базу');
            }
        }

        return $result;
    }

    public function bindLinkToChat(Chat $chat, string $link): ResultContainer
    {
        $result = new ResultContainer();
        $linkService = new LinkService();

        if ($linkText = $this->getNormalizedUrl($link)) {
            $link = $linkService->getLinkOrCreate($linkText);

            if ($link instanceof Link) {
                try {
                    $chat->links()->attach($link->id);
                    $result->setSuccess(true)->setMessage('Ссылка успешно добавлена');
                } catch (\Exception) {
                    $result->setSuccess(false)->setMessage('В этом чате уже есть такая ссылка');
                }
            } else {
                $result->setSuccess(false)->setMessage('При добавлении ссылки произошла ошибка');
            }
        } else {
            $result->setSuccess(false)
                ->addLine('Некорректный формат ссылки')
                ->addLine('/add <ссылка>');
        }

        return $result;
    }
}
