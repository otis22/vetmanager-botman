<?php


namespace App\Vetmanager\Notification\Routers;


class EveryoneRoute implements NotificationRouteInterface
{
    /**
     * @var array
     */
    private $users;

    /**
     * EveryoneRoute constructor.
     * @param $users
     */
    public function __construct($users)
    {
        $this->users = $users;
    }

    /**
     * @return array
     */
    public function asArray(): array
    {
        return $this->users;
    }
}