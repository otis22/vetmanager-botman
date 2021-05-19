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
    /**
     * @var MessageInterface
     */
    private $message;
    /**
     * @var NotificationRouteInterface
     */
    private $route;
    /**
     * @var SendAction
     */
    private $sendAction;

    public function __construct(MessageInterface $message, NotificationRouteInterface $route, SendActionInterface $sendAction)
    {
        $this->message = $message;
        $this->route = $route;
        $this->sendAction = $sendAction;
    }

    public function send()
    {
        foreach ($this->route->asArray() as $user) {
            $this->sendAction->do($this->message, $user, $this->driver($user->channel));
        }
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