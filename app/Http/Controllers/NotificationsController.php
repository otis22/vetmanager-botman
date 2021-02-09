<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use BotMan\Drivers\Telegram\TelegramDriver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Helpers\VmEvent;


final class NotificationsController extends Controller
{
    public function handleNotifications(Request $request, $domain)
    {
        $botman = resolve('botman');
        $input = $request->all();
        $event = new VmEvent($input['name']);
        if ($event->hasTranslation()) {
            $users = DB::table('users')
                ->where('clinic_domain', '=', $domain)
                ->where('notification_enabled', '=', true)->pluck('chat_id')->toArray();
            $botman->say($event->asString(), $users, TelegramDriver::class);
        }
    }
}
