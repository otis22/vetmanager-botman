<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class ServiceConversation extends VetmanagerConversation
{
    private function auth()
    {
        $userId = $this->user()->getVmUserId();
        $domainName = $this->user()->getDomain();
        $md5 = md5($domainName . $userId);

        return $md5;
    }

    private function todayVisitLink()
    {
        $this->say("https://vetmanager-botman.herokuapp.com/shield/" . $this->auth() . "/" . 'today' . "/");
        $this->endConversation();
    }

    private function weekVisitLink()
    {
        $this->say("https://vetmanager-botman.herokuapp.com/shield/" . $this->auth() . "/" . 'week' . "/");
        $this->endConversation();
    }

    private function askVisitCount()
    {
        $question = Question::create('Счетчик визитов');
        $question->addButtons([
            Button::create('Счетчик посещений за сегодня')->value('today'),
            Button::create('Счетчик посещений за 7 дней')->value('week')
        ]);
        $this->ask($question, function (Answer $answer){
        $value = $answer->getText();
        if($value == 'today') {
            return $this->todayVisitLink();
        } else {
            return $this->weekVisitLink();
        }});
     }

     public function run ()
    {
        $this->askVisitCount();
    }
}