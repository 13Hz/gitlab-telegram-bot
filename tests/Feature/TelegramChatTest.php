<?php

namespace Tests\Feature;

use App\Models\Chat;
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
}
