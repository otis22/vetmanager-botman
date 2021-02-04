<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Conversations\AuthConversation;
use App\Conversations\NotificationConversation;
use App\Conversations\TimesheetConversation;
use App\Conversations\AdmissionConversation;
use BotMan\BotMan\BotMan;

final class VetmanagerController extends Controller
{
    /**
     * Loaded through routes/botman.php
     * @param  BotMan $bot
     */
    public function authConversation(BotMan $bot)
    {
        $bot->startConversation(new AuthConversation(config('app.name')));
    }

    public function timesheetConversation(BotMan $bot)
    {
        $bot->startConversation(new TimesheetConversation());
    }

    public function admissionConversation(BotMan $bot)
    {
        $bot->startConversation(new AdmissionConversation());
    }

    public function notificationConversation(BotMan $bot)
    {
        $bot->startConversation(new NotificationConversation());
    }
}
