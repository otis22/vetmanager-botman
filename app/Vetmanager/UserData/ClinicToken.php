<?php

declare(strict_types=1);

namespace App\Vetmanager\UserData;

use App\Exceptions\VmUnauthorizedException;
use App\Vetmanager\UserData\UserRepository\User;
use ElegantBro\Interfaces\Stringify;

final class ClinicToken implements Stringify
{
    /**
     * @var User
     */
    private $user;

    /**
     * ClinicToken constructor.
     * @param User $user
     */

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function asString(): string
    {
        $token = $this->user->getToken();
        if (empty($token)) {
            throw new VmUnauthorizedException("Попробуйте повторить команду после авторизации.");
        }
        return strval($token);
    }

}
