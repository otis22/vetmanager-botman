<?php

declare(strict_types=1);

namespace App\Vetmanager\UserData;

use BotMan\BotMan\BotMan;
use ElegantBro\Interfaces\Stringify;
use Exception;

final class ClinicToken implements Stringify
{
    /**
     * @var BotMan
     */
    private $bot;

    /**
     * ClinicToken constructor.
     * @param BotMan $bot
     */
    public function __construct(BotMan $bot)
    {
        $this->bot = $bot;
    }

    public function asString(): string
    {
        $token = $this->bot
            ->userStorage()->get('clinicUserToken');

        if (empty($token)) {
            throw new \Exception("Token can't be empty");
        }
        return strval($token);
    }

}
