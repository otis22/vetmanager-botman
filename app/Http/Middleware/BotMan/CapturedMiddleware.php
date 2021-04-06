<?php


namespace App\Http\Middleware\BotMan;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Interfaces\Middleware\Captured;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use Illuminate\Support\Facades\DB;

class CapturedMiddleware implements Captured
{
    /**
     * Handle an incoming message.
     *
     * @param IncomingMessage $message
     * @param callable $next
     * @param BotMan $bot
     *
     * @return mixed
     */
    public function captured(IncomingMessage $message, $next, BotMan $bot)
    {
        DB::table('statistic')->insert([
            'created_at' => date("Y-m-d H:i:s"),
            'user_id' => $message->getSender(),
            'channel' => $bot->getDriver()->getName(),
            'event' => 'incoming message'
        ]);

        return $next($message);
    }
}
