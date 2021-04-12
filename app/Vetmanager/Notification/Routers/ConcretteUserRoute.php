<?php


namespace App\Vetmanager\Notification\Routers;


class ConcretteUserRoute implements NotificationRouteInterface
{
    /**
     * @var array
     */
    private $user;

    /**
     * ConcretteUserRoute constructor.
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    public function asArray(): array
    {
        return $this->user;
    }
}