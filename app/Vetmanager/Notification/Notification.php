<?php


namespace App\Vetmanager\Notification;


use App\Vetmanager\Logging\LoggerInterface;
use App\Vetmanager\Notification\Messages\MessageInterface;
use App\Vetmanager\Notification\Routers\NotificationRouteInterface;
use BotMan\BotMan\BotMan;
use BotMan\Drivers\Telegram\TelegramDriver;
use BotMan\Drivers\Web\WebDriver;

class Notification
{
    private $message;
    private $route;
    private $botman;

    public function __construct(MessageInterface $message, NotificationRouteInterface $route, BotMan $botman)
    {
        $this->message = $message;
        $this->route = $route;
        $this->botman = $botman;
    }

    public function send()
    {
        foreach ($this->route->asArray() as $user) {
            $this->botman->say($this->message->asString(), $user->chat_id, $this->driver($user->channel));
            if (isset($this->logger)) {
                $this->logger->log($user);
            }
        }
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    private function driver($channel)
    {
        switch ($channel) {
            case 'Telegram':
                return TelegramDriver::class;
            case 'Web':
                return WebDriver::class;
        }
    }
}