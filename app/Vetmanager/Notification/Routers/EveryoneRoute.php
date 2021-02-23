<?php


namespace App\Vetmanager\Notification\Routers;


class EveryoneRoute implements NotificationRouteInterface
{
    /**
     * @var array
     */
    private $users;

    /**
     * @var array
     */
    private $data;

    /**
     * EveryoneRoute constructor.
     * @param $users
     * @param $data
     */
    public function __construct($users, $data)
    {
        $this->users = $users;
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function asArray(): array
    {
        return array_column($this->users, 'chat_id');
    }
}