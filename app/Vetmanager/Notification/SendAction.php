<?php


namespace App\Vetmanager\Notification;


use App\Vetmanager\Logging\LoggerInterface;
use App\Vetmanager\Notification\Messages\MessageInterface;
use BotMan\BotMan\BotMan;

class SendAction
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
        $this->botman->say($message->asString(), $user->chat_id, $driver);
        $this->logger->log($user);
    }

}