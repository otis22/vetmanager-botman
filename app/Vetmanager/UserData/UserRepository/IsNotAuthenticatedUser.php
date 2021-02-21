<?php

declare(strict_types=1);

namespace App\Vetmanager\UserData\UserRepository;

final class IsNotAuthenticatedUser implements UserInterface
{

    public function getId()
    {
        throw new \Exception("Is not authenticated user");
    }

    public function getDomain()
    {
        throw new \Exception("Is not authenticated user");
    }

    public function getToken()
    {
        throw new \Exception("Is not authenticated user");
    }

    public function getVmUserId()
    {
        throw new \Exception("Is not authenticated user");
    }

    public function isNotificationEnabled(): bool
    {
        throw new \Exception("Is not authenticated user");
    }

    public function enableNotifications()
    {
        throw new \Exception("Is not authenticated user");
    }

    public function disableNotifications()
    {
        throw new \Exception("Is not authenticated user");
    }

    public function toArray(): array
    {
        throw new \Exception("Is not authenticated user");
    }

    public function isAuthorized(): bool
    {
        return false;
    }
}
