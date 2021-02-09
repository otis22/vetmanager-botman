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
        $input = $request->all();
        $botman = resolve('botman');
        $event = new VmEvent($input['name']);
        if ($event->hasTranslation()) {
            $users = DB::table('users')
                ->where('clinic_domain', '=', $domain)
                ->where('notification_enabled', '=', true)->pluck('chat_id')->toArray();

            foreach ($users as $user) {
                DB::table('statistic')->insert([
                    'created_at' => date("Y-m-d H:i:s"),
                    'user_id' => $user,
                    'channel' => $botman->getDriver()->getName(),
                    'event' => 'notification message'
                ]);
                $botman->say($event->asString(), $user, TelegramDriver::class);
            }
        }
    }
}
