<?php


namespace App\Vetmanager\Notification\Messages;


use ElegantBro\Interfaces\Stringify;

interface MessageInterface extends Stringify
{
    public function __construct($data);
}