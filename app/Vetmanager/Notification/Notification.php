<?php


namespace App\Vetmanager\Notification;


use App\Vetmanager\Notification\Messages\MessageInterface;
use App\Vetmanager\Notification\Routers\NotificationRouteInterface;
use BotMan\BotMan\BotMan;
use BotMan\Drivers\Telegram\TelegramDriver;
use Illuminate\Support\Facades\DB;

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
            $this->botman->say($this->message->asString(), $user, TelegramDriver::class);

            DB::table('statistic')->insert([
                'created_at' => date("Y-m-d H:i:s"),
                'user_id' => $user,
                'channel' => $this->botman->getDriver()->getName(),
                'event' => 'notification message'
            ]);
        }
    }
}