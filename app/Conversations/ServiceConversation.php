<?php

namespace App\Conversations;

use Abyzs\VetmanagerVisits\AuthToken;
use Abyzs\VetmanagerVisits\VisitCounter;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class ServiceConversation extends VetmanagerConversation
{
    private function Auth(): array
    {
        $userId = $this->user()->getVmUserId();
        $domainName = $this->user()->getDomain();
        $appName = config('app.name');
        $token = $this->user()->getToken();
        $md5 = md5($domainName . $userId);
        $auth = new AuthToken($domainName, $appName, $token);

        return $auth->getInvoices();
    }

    private function dayCount(): int
    {
        $dayCount = new VisitCounter();

            if($dayCount->dayCount($this->Auth()) >= 1000) {
                return $dayCount->dayCount($this->Auth())/1000 . "k";
            }
            else {
                return $dayCount->dayCount($this->Auth());
            }
    }

    private function weekCount(): int
    {
        $weekCount = new VisitCounter();

             if($weekCount->weekCount($this->Auth()) >= 1000) {
                 return $weekCount->weekCount($this->Auth())/1000 . "k";
             }
             else {
                 return $weekCount->weekCount($this->Auth());
             }
    }

    public function askVisitCount()
    {
        $question = Question::create('Счетчик визитов');
        $question->addButtons( [
            Button::create('Визиты за день')->value($this->dayCount()),
            Button::create('Визиты за неделю')->value($this->weekCount())
        ]);

        $this->ask($question, function (Answer $response) {
             $this->say('Количество визитов - ' . $response->getValue());
        });
    }

    public function run ()
    {
        $this->askVisitCount();
    }
}