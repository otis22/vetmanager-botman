<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use BotMan\BotMan\BotManFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


final class NotificationsController extends Controller
{
    public function handleNotifications(Request $request, $domain)
    {
        $botman = BotManFactory::create([]);
        $input = $request->all();
        $users = DB::table('users')
            ->where('clinic_domain', '=', $domain)
            ->where('notification_enabled', '=', true)->pluck('chat_id')->toArray();
        $botman->say($input['name'], $users);
    }
}
