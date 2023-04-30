<?php

namespace Tests\Feature;

use App\Models\Chat;
use App\Services\ChatButtonService;
use App\Services\TelegramChatService;
use Tests\TestCase;

class TelegramChatTest extends TestCase
{
    public function testRegisterChat()
    {
        $chatData = Chat::factory()->definition();

        $chatService = new TelegramChatService();
        $result = $chatService->createChat($chatData['chat_id'], $chatData['type']);

        $this->assertTrue($result->isSuccess());
        $this->assertDatabaseHas('chats', $chatData);
    }

    public function testErrorIsChatAlreadyExists()
    {
        $chatData = Chat::factory()->definition();
        $newChatData = Chat::factory()->definition();
        $newChatData['chat_id'] = $chatData['chat_id'];

        $chatService = new TelegramChatService();
        $chatService->createChat($chatData['chat_id'], $chatData['type']);
        $result = $chatService->createChat($newChatData['chat_id'], $newChatData['type']);

        $this->assertFalse($result->isSuccess());
        $this->assertDatabaseHas('chats', $chatData);
        $this->assertDatabaseMissing('chats', $newChatData);
    }

    public function testRepositoriesListCommand()
    {
        $chatButtonService = new ChatButtonService();
        $telegramChatService = new TelegramChatService();

        $chat = Chat::factory()->create();
        $testLink = 'https://gitlab.com/test/example/';
        $bindResult = $telegramChatService->bindLinkToChat($chat, $testLink);

        if ($bindResult->isSuccess()) {
            $link = $chat->links()->first();
            if ($link) {
                $repositoryName = $link->repository_name;
                $linksButtons = $chatButtonService->getRepositoriesKeyboard($chat->chat_id);
                if ($linksButtons) {
                    $exists = false;
                    $rawData = $linksButtons->getRawData();
                    if (!empty($rawData['inline_keyboard'])) {
                        foreach ($rawData['inline_keyboard'] as $buttons) {
                            foreach ($buttons as $button) {
                                if ($button->getText() === $repositoryName) {
                                    $exists = true;
                                    break;
                                }
                            }

                            if ($exists) {
                                break;
                            }
                        }
                    }

                    $this->assertTrue($exists);
                }

                $this->assertNotEmpty($linksButtons);
                $this->assertStringContainsString($repositoryName, $testLink);
            }

            $this->assertNotEmpty($link);
        }

        $this->assertTrue($bindResult->isSuccess());
    }
}
