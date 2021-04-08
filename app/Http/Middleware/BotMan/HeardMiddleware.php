<?php


namespace App\Http\Middleware\BotMan;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Interfaces\Middleware\Captured;
use BotMan\BotMan\Interfaces\Middleware\Heard;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use Illuminate\Support\Facades\DB;

class HeardMiddleware implements Heard
{
    /**
     * Handle a message that was successfully heard, but not processed yet.
     *
     * @param IncomingMessage $message
     * @param callable $next
     * @param BotMan $bot
     *
     * @return mixed
     */
    public function heard(IncomingMessage $message, $next, BotMan $bot)
    {
        DB::table('statistic')->insert([
            'created_at' => date("Y-m-d H:i:s"),
            'user_id' => $message->getSender(),
            'channel' => $bot->getDriver()->getName(),
            'event' => 'incoming message',
            'message' => $message->getText()
        ]);

        return $next($message);
    }
}
