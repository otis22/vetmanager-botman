<?php

namespace App\Vetmanager\UserData;

use App\Vetmanager\UserData\UserRepository\UserRepository;
use ElegantBro\Interfaces\Stringify;
use Illuminate\Validation\UnauthorizedException;

final class ClinicUrl implements Stringify
{
    private $urlBuilder;

    /**
     * @var UserRepository
     */
    private $user;

    /**
     * ClinicUrl constructor.
     * @param callable $urlBuilder
     * @param UserRepository $user
     */
    public function __construct($urlBuilder, UserRepository $user)
    {
        $this->urlBuilder = $urlBuilder;
        $this->user = $user;
    }

    public function asString(): string
    {
        $clinicDomain = $this->user->getDomain();
        if (empty($clinicDomain)) {
            throw new UnauthorizedException("Попробуйте повторить команду после авторизации.");
        }
        $builder = $this->urlBuilder;

        $clinicUrl = $builder($clinicDomain);
        return strval($clinicUrl);
    }
}
