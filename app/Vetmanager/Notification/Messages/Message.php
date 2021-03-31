<?php

namespace App\Vetmanager\Notification\Messages;


class Message implements MessageInterface
{
    /**
     * @var string
     */
    private $message;

    /**
     * RollbackMessage constructor.
     * @param $message
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    public function asString(): string
    {
        return $this->message;
    }

}