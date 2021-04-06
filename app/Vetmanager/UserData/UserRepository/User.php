<?php


namespace App\Vetmanager\UserData\UserRepository;


class User implements UserInterface
{
    protected $chatId;
    protected $domain;
    protected $token;
    protected $vmUserId;
    protected $channel;
    protected $notificationEnabled;
    protected $is_blocked;

    public function __construct($chatId, $domain, $token, $vmUserId, $channel, $notificationEnabled, $is_blocked)
    {
        $this->chatId = $chatId;
        $this->domain = $domain;
        $this->token = $token;
        $this->vmUserId = $vmUserId;
        $this->channel = $channel;
        $this->notificationEnabled = $notificationEnabled;
        $this->is_blocked = $is_blocked;
    }

    public function getId()
    {
        return $this->chatId;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getVmUserId()
    {
        return $this->vmUserId;
    }

    public function getChannel()
    {
        return $this->channel;
    }

    public function isNotificationEnabled(): bool
    {
        return $this->notificationEnabled;
    }

    public function enableNotifications()
    {
        $this->notificationEnabled = true;
    }

    public function disableNotifications()
    {
        $this->notificationEnabled = false;
    }

    public function isBlocked(): bool
    {
        return $this->is_blocked;
    }

    public function block()
    {
        $this->is_blocked = 1;
    }

    public function unblock()
    {
        $this->is_blocked = 0;
    }

    public function toArray(): array
    {
        return [
            'chat_id' => $this->getId(),
            'clinic_domain' => $this->getDomain(),
            'clinic_token' => $this->getToken(),
            'vm_user_id' => $this->getVmUserId(),
            'channel' => $this->getChannel(),
            'notification_enabled' => $this->isNotificationEnabled(),
            'is_blocked' => $this->isBlocked()
        ];
    }

    public function isAuthorized(): bool
    {
        return !empty($this->token);
    }
}