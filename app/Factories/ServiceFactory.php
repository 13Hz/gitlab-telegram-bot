<?php

namespace App\Factories;

use App\Builders\ServiceBuilder;
use App\Services\GitlabRequestService;
use App\Services\TelegramRequestService;
use Illuminate\Http\Request;

class ServiceFactory
{
    /**
     * @param Request $request
     * @return ServiceBuilder
     */
    public static function factory(Request $request): ServiceBuilder
    {
        if ($request->header(config('telegram.gitlab.header')) === config('telegram.gitlab.token')) {
            return new GitlabRequestService($request);
        } elseif($request->header(config('telegram.webhook.header')) === config('telegram.webhook.token')) {
            return new TelegramRequestService($request);
        }

        abort(response('', 404));
    }
}
