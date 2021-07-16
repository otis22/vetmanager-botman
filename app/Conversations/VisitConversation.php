<?php

namespace App\Conversations;

use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer as BotManAnswer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\BotMan\Messages\Outgoing\Question as BotManQuestion;
use Doctrine\DBAL\Connection;
use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use BotMan\BotMan\Messages\Incoming\Answer;

class VisitConversation extends Conversation
{
    protected $firstname;
    protected $email;

    public function askFirstname()
{
    $this->ask('Hello! What is your firstname?', function(Answer $answer) {
        // Save result
        $this->firstname = $answer->getText();

        $this->say('Nice to meet you '.$this->firstname);
        $this->askEmail();
    });
}

    public function askEmail()
    {
        $this->ask('One more thing - what is your email?', function(Answer $answer) {
            // Save result
            $this->email = $answer->getText();

            $this->say('Great - that is all we need, '.$this->firstname);
        });
    }

    public function run()
    {
        dd('test');// This will be called immediately
        $this->askFirstname();
    }
}