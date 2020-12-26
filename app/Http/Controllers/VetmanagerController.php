<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Conversations\AuthConversation;
use BotMan\BotMan\BotMan;

final class VetmanagerController extends Controller
{
    /**
     * Loaded through routes/botman.php
     * @param  BotMan $bot
     */
    public function authConversation(BotMan $bot)
    {
        $bot->startConversation(new AuthConversation());
    }
}
