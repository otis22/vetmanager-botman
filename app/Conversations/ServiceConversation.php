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

    private function visitLink()
    {
        $this->say("https://vetmanager-botman.herokuapp.com/shield/" . $this->auth() . "/" . 'all' . "/");
        $this->endConversation();
    }

    private function askVisitCount()
    {
        $question = Question::create('Счетчик визитов');
        $question->addButtons([
            Button::create('Счетчик посещений')->value('visit'),
        ]);
        $this->ask($question, function (Answer $answer){
        $value = $answer->getText();
        if($value == 'visit') {
            return $this->visitLink();
        } else {
            throw new \Exception('Неизвестная команда');
        }});
     }

     public function run ()
    {
        $this->askVisitCount();
    }
}