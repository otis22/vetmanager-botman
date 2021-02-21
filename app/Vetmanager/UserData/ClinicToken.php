<?php

declare(strict_types=1);

namespace App\Vetmanager\UserData;

use App\Exceptions\VmUnauthorizedException;
use App\Vetmanager\UserData\UserRepository\UserInterface;
use ElegantBro\Interfaces\Stringify;

final class ClinicToken implements Stringify
{
    /**
     * @var UserInterface
     */
    private $user;

    /**
     * ClinicToken constructor.
     * @param UserInterface $user
     */

    public function __construct(UserInterface $user)
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
