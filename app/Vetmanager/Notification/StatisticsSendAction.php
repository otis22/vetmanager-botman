<?php


namespace App\Vetmanager\Notification;


use App\Vetmanager\Logging\LoggerInterface;
use App\Vetmanager\Notification\Messages\MessageInterface;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class StatisticsSendAction implements SendActionInterface
{
    private $botman;
    private $logger;

    public function __construct(BotMan $botman, LoggerInterface $logger)
    {
        $this->botman = $botman;
        $this->logger = $logger;
    }

    public function do(MessageInterface $message, $user, $driver)
    {
        $button = Question::create($message->asString())
            ->addButtons([Button::create("Оцените работу Vetmanager Bot")->value('review')]);
        $this->botman->say($button, $user->chat_id, $driver);
        $this->logger->log($user);
    }

}