<?php

declare(strict_types=1);

namespace App\Vetmanager\Api;

use App\Vetmanager\UserData\ClinicToken;
use App\Vetmanager\UserData\ClinicUrl;
use App\Vetmanager\UserData\UserRepository\UserInterface;
use GuzzleHttp\Client;
use Otis22\VetmanagerToken\Token;
use Otis22\VetmanagerToken\Token\Concrete;

use function Otis22\VetmanagerUrl\url;

final class AuthenticatedClientFactory
{
    /**
     * @var UserInterface
     */
    private $user;

    /**
     * ClientFactory constructor.
     * @param UserInterface $user
     */
    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    private function token(): Token
    {
        return new Concrete(
            (
                new ClinicToken(
                    $this->user
                )
            )->asString()
        );
    }

    private function baseUrl(): ClinicUrl
    {
        return new ClinicUrl(
            function (string $domain): string {
                return url($domain)->asString();
            },
            $this->user
        );
    }

    public function create(): Client
    {
        return new Client(
            [
                'base_uri' => $this->baseUrl()->asString(),
                'headers' => [
                    'X-USER-TOKEN' => $this->token()->asString(),
                    'X-APP-NAME' => config('app.name')
                ]
            ]
        );
    }
}
