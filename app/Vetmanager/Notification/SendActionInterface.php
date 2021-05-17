<?php


namespace App\Vetmanager\Notification;


use App\Vetmanager\Notification\Messages\MessageInterface;

interface SendActionInterface
{
    public function do(MessageInterface $message, $user, $driver);
}