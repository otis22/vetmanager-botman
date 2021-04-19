<?php

declare(strict_types=1);

namespace App\Conversations;

use App\Http\Helpers\Rest\AdmissionApi;
use App\Vetmanager\Api\AuthenticatedClientFactory;
use App\Vetmanager\MainMenu;
use App\Vetmanager\MessageBuilder\Admission\AdmissionMessageBuilder;
use App\Vetmanager\UserData\UserRepository\UserInterface;
use App\Vetmanager\UserData\UserRepository\UserRepository;
use BotMan\BotMan\Messages\Conversations\Conversation;
use App\Http\Helpers\Rest\UsersApi;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;


final class StatsConversation extends VetmanagerConversation
{

    public function showStatsUrl()
    {
        $this->say("http://vetmanager-botman.herokuapp.com/stats");
        $this->endConversation();
    }


    public function run()
    {
        $this->showStatsUrl();
    }
}
