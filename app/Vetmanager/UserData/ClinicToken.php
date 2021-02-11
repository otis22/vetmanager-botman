<?php

declare(strict_types=1);

namespace App\Vetmanager\UserData;

use App\Exceptions\VmUnauthorizedException;
use App\Vetmanager\UserData\UserRepository\UserRepository;
use ElegantBro\Interfaces\Stringify;

final class ClinicToken implements Stringify
{
    /**
     * @var UserRepository
     */
    private $user;

    /**
     * ClinicToken constructor.
     * @param UserRepository $user
     */

    public function __construct(UserRepository $user)
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
