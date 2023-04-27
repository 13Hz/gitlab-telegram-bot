<?php

namespace App\Services;

use App\Builders\ServiceBuilder;
use App\Models\Chat;
use App\Models\Core\ReceivedRequest;
use App\Models\Core\Telegram;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class TelegramRequestService extends ReceivedRequest implements ServiceBuilder
{
    public function process(): Response
    {
        Log::info(print_r([$_REQUEST, getallheaders(), file_get_contents('php://input')], true));
        $telegram = Telegram::getInstance();
        $telegram->enableAdmins(Chat::where('is_admin', true)->pluck('chat_id')->toArray());
        $telegram->setCommandsPath(app_path('Commands'))->handle();

        return \response('ok');
    }
}
