<?php
/**
 * Created by PhpStorm.
 * User: danilyer
 * Date: 01.02.21
 * Time: 9:46
 */

namespace App\Vetmanager\UserData;

use ElegantBro\Interfaces\Stringify;
use BotMan\BotMan\BotMan;

final class ClinicUrl implements Stringify
{
    /**
     * @var BotMan
     */
    private $bot;

    /**
     * ClinicUrl constructor.
     * @param BotMan $bot
     */
    public function __construct(BotMan $bot)
    {
        $this->bot = $bot;
    }

    public function asString(): string
    {
        $clinicUrl = $this->bot
            ->userStorage()->get('clinicUrl');

        if (empty($clinicUrl)) {
            throw new \Exception("Clinic url can't be empty");
        }
        return strval($clinicUrl);
    }
}