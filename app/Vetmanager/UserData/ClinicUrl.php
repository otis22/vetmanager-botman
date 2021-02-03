<?php

namespace App\Vetmanager\UserData;

use ElegantBro\Interfaces\Stringify;
use BotMan\BotMan\BotMan;
use Otis22\VetmanagerUrl\Url\FromBillingApiGateway;

use function Otis22\VetmanagerUrl\url;

final class ClinicUrl implements Stringify
{
    /**
     * @var BotMan
     */
    private $bot;

    /**
     * @var FromBillingApiGateway
     */
    private $urlBuilder;

    /**
     * ClinicUrl constructor.
     * @param BotMan $bot
     * @param callable $urlBuilder
     */
    public function __construct(BotMan $bot, $urlBuilder)
    {
        $this->bot = $bot;
        $this->urlBuilder = $urlBuilder;
    }

    public function asString(): string
    {
        $clinicDomain = $this->bot
            ->userStorage()->get('clinicDomain');
        $builder = $this->urlBuilder;

        if (empty($clinicDomain)) {
            throw new \Exception("Clinic url can't be empty");
        }
        $clinicUrl = $builder($clinicDomain);
        return strval($clinicUrl);
    }
}
