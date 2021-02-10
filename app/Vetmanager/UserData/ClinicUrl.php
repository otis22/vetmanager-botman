<?php

namespace App\Vetmanager\UserData;

use ElegantBro\Interfaces\Stringify;
use BotMan\BotMan\BotMan;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\UnauthorizedException;
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
        $userId = $this->bot->getUser()->getId();
        $clinicDomain = $this->bot
            ->userStorage()->get('clinicDomain');
        if(empty($clinicDomain)) {
            $clinicDomain = DB::table('users')->where('chat_id', '=', $userId)->get('clinic_domain')->toArray();
            if (empty($clinicDomain)) {
                throw new UnauthorizedException("Попробуйте повторить команду после авторизации.");
            }
        }
        $builder = $this->urlBuilder;

        $clinicUrl = $builder($clinicDomain);
        return strval($clinicUrl);
    }
}
