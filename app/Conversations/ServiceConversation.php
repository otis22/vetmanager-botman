<?php

namespace App\Conversations;


use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class ServiceConversation extends VetmanagerConversation
{
    public function askVisitCount()
    {
        $userId = $this->user()->getVmUserId();
        $domainName = $this->user()->getDomain();
        $md5 = md5($domainName . $userId);

        $question = Question::create('Счетчик визитов');
        $question->addButtons([
            Button::create('Счетчик посещений за сегодня')->value("http://127.0.0.1:8000/visits/" . $md5 . "/" . 'today'),
            Button::create('Счетчик посещений за 7 дней')->value("http://127.0.0.1:8000/visits/" . $md5 . "/" . 'week' . "/")
        ]);
        $this->ask($question, function (Answer $response) {
            $this->say($response->getText());
            $this->endConversation();
        });
    }

    public function run ()
    {
        $this->askVisitCount();
    }
}