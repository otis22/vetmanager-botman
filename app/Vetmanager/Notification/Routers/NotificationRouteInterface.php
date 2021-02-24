<?php


namespace App\Vetmanager\Notification\Routers;


interface NotificationRouteInterface
{
    /**
     * NotificationRouteInterface constructor.
     * @param $users
     * @param $data
     */
    public function __construct($users, $data);

    /**
     * @return mixed
     */
    public function asArray();
}