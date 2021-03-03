<?php


namespace App\Vetmanager\Notification\Routers;


class ConcretteUserRoute implements NotificationRouteInterface
{
    /**
     * @var array
     */
    private $user;

    /**
     * @var array
     */
    private $data;

    /**
     * ConcretteUserRoute constructor.
     * @param $user
     * @param $data
     */
    public function __construct($user, $data)
    {
        $this->user = $user;
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function asArray(): array
    {
        return $this->user;
    }
}