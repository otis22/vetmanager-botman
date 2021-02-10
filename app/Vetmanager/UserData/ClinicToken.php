<?php

declare(strict_types=1);

namespace App\Vetmanager\UserData;

use App\Exceptions\VmUnauthorizedException;
use BotMan\BotMan\BotMan;
use ElegantBro\Interfaces\Stringify;
use Illuminate\Support\Facades\DB;

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
        $userId = $this->bot->getUser()->getId();
        $token = $this->bot
            ->userStorage()->get('clinicUserToken');
        if (empty($token)) {
            $token = DB::table('users')->where('chat_id', '=', $userId)->get('clinic_token');
            if (empty($token)) {
                throw new VmUnauthorizedException("Попробуйте повторить команду после авторизации.");
            }
        }
        return strval($token);
    }

}
