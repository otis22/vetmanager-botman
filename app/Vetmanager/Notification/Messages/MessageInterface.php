<?php


namespace App\Vetmanager\Notification\Messages;


interface MessageInterface
{
    public function __construct($data);
    public function asString();
}