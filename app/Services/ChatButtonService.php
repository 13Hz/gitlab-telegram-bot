<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\ChatLink;
use App\Models\Core\InlineKeyboard;
use App\Models\Trigger;

class ChatButtonService
{
    /** Получить кнопочную клавиатуру связанных репозиториев с указанным чатом
     * @param string|int $chatId Идентификатор чата
     * @return InlineKeyboard|null Кнопки репозиториев
     */
    public function getRepositoriesKeyboard(string|int $chatId): InlineKeyboard|null
    {
        $chat = Chat::where('chat_id', $chatId)->first();
        if ($chat) {
            $links = $chat->links()->get();
            if ($links) {
                $buttons = [];

                foreach ($links as $link) {
                    $buttons[] = [
                        [
                            'text' => $link->repository_name,
                            'callback_data' => json_encode([
                                'entity' => 'link',
                                'action' => 'show_buttons',
                                'id' => $link->id
                            ])
                        ]
                    ];
                }

                return new InlineKeyboard($buttons);
            }
        }

        return null;
    }

    /** Получить клавиатуру действий для выбранного репозитория
     * @param string|int $entityId Идентификатор сущности
     * @return InlineKeyboard Клавиатура с действиями
     */
    public function getActionsKeyboard(string|int $entityId): InlineKeyboard
    {
        return new InlineKeyboard([
            [
                [
                    'text' => 'Удалить',
                    'callback_data' => json_encode([
                        'entity' => 'link',
                        'action' => 'delete',
                        'id' => $entityId
                    ])
                ]
            ],
            [
                [
                    'text' => 'Фильтр',
                    'callback_data' => json_encode([
                        'entity' => 'link',
                        'action' => 'filter',
                        'id' => $entityId
                    ])
                ]
            ],
            [
                [
                    'text' => 'Назад',
                    'callback_data' => json_encode([
                        'entity' => 'link',
                        'action' => 'show_list',
                        'id' => $entityId
                    ])
                ]
            ]
        ]);
    }

    /** Получить клавиатуру фильтрации для выбранного репозитория
     * @param  ChatLink  $chatLink Связка чата и репозитория
     * @return InlineKeyboard Клавиатура с фильтрами
     */
    public function getFiltersKeyboard(string|int $entityId, ChatLink $chatLink): InlineKeyboard
    {
        $triggerFilterService = new TriggerFilterService();
        $triggers = Trigger::all();
        $keyboard = [];
        foreach ($triggers as $trigger) {
            $enabledText = $this->getFilterEnabledText($triggerFilterService->getTriggerState($chatLink, $trigger));
            $keyboard[] = [[
                'text' => "$enabledText $trigger->title",
                'callback_data' => json_encode([
                    'entity' => 'filter',
                    'action' => $trigger->code,
                    'id' => $entityId,
                    'trigger' => $trigger->id
                ])
            ]];
        }
        $keyboard[] = [
            [
                'text' => 'Назад',
                'callback_data' => json_encode([
                    'entity' => 'link',
                    'action' => 'show_buttons',
                    'id' => $entityId
                ])
            ]
        ];

        return new InlineKeyboard($keyboard);
    }

    /**Получить текст состояния
     * @param bool $flag
     * @return string
     */
    private function getFilterEnabledText(bool $flag): string
    {
        return $flag ? '[ВКЛ]' : '[ВЫКЛ]';
    }
}
