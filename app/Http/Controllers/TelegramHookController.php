<?php

namespace App\Http\Controllers;

use App\Factories\ServiceFactory;
use Illuminate\Http\Request;

class TelegramHookController extends Controller
{
    public function handle(Request $request)
    {
        return ServiceFactory::factory($request)->process();
    }
}
