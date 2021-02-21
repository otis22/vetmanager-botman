<?php

namespace App\Vetmanager\UserData\UserRepository;

interface UserInterface
{
    public function getId();

    public function getDomain();

    public function getToken();

    public function getVmUserId();

    public function isNotificationEnabled(): bool;

    public function enableNotifications();

    public function disableNotifications();

    public function toArray(): array;

    public function isAuthorized(): bool;
}