<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Conversations\AuthConversation;
use App\Conversations\ClientBriefConversation;
use App\Conversations\NotificationConversation;
use App\Conversations\PriceListConversation;
use App\Conversations\ReviewConversation;
use App\Conversations\StatsConversation;
use App\Conversations\TimesheetConversation;
use App\Conversations\AdmissionConversation;
use BotMan\BotMan\BotMan;

final class VetmanagerController extends Controller
{
    /**
     * Loaded through routes/botman.php
     */
    public function authConversation(BotMan $bot)
    {
        $bot->startConversation(new AuthConversation());
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

    public function reviewConversation(BotMan $bot)
    {
        $bot->startConversation(new ReviewConversation());
    }

    public function statsConversation(BotMan $bot)
    {
        $bot->startConversation(new StatsConversation());
    }

    public function clientBriefConversation(BotMan $bot, $id)
    {
        $bot->startConversation(new ClientBriefConversation($id));
    }

    public function priceListConversation(BotMan $bot)
    {
        $bot->startConversation(new PriceListConversation());
    }
}
