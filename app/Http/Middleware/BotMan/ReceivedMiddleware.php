<?php


namespace App\Http\Middleware\BotMan;

use BotMan\BotMan\Interfaces\Middleware\Received;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\BotMan\BotMan;
use Illuminate\Support\Facades\DB;

class ReceivedMiddleware implements Received
{
    /**
     * Handle an incoming message.
     *
     * @param callable $next
     *
     * @return mixed
     */
    public function received(IncomingMessage $message, $next, BotMan $bot)
    {
        return $next($message);
    }
}

