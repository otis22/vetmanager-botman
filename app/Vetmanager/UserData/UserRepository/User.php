<?php


namespace App\Vetmanager\UserData\UserRepository;


class User
{
    protected $chatId;
    protected $domain;
    protected $token;
    protected $notificationEnabled;

    public function __construct($chatId, $domain, $token, $notificationEnabled=false)
    {
        $this->chatId = $chatId;
        $this->domain = $domain;
        $this->token = $token;
        $this->notificationEnabled = $notificationEnabled;
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

    public function toArray(): array
    {
        return [
            'chat_id' => $this->getId(),
            'clinic_domain' => $this->getDomain(),
            'clinic_token' => $this->getToken(),
            'notification_enabled' => $this->isNotificationEnabled()
        ];
    }
}