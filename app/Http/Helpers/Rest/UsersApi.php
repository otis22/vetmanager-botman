<?php

namespace App\Http\Helpers\Rest;

use GuzzleHttp\Client;
use Otis22\VetmanagerRestApi\Model\Property;
use Otis22\VetmanagerRestApi\Query\Filter\EqualTo;
use Otis22\VetmanagerRestApi\Query\Filter\Value\StringValue;
use Otis22\VetmanagerRestApi\Query\Filters;

use function Otis22\VetmanagerRestApi\uri;

class UsersApi
{
    /**
     * @var Client
     */
    private $httpClient;

    /**
     * Users constructor.
     */
    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @return array{success:bool}
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function all(): array
    {
        $request = $this->httpClient->request(
            'GET',
            uri("user")->asString()
        );
        return json_decode(
            strval(
                $request->getBody()
            ),
            true
        );
    }

    public function allActive(): array
    {
        $filters = new Filters(
            new EqualTo(
                new Property('is_active'),
                new StringValue(strval(1))
            ),
            new EqualTo(
                new Property('is_limited'),
                new StringValue(strval(0))
            )
        );
        $request = $this->httpClient->request(
            'GET',
            uri("user")->asString() . '/',
            [
                "query" => $filters->asKeyValue()
            ]
        );
        return json_decode(
            strval(
                $request->getBody()
            ),
            true
        );
    }

    /**
     * @int $id
     * @return array{success:bool}
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function byId(int $id): array
    {
        $request = $this->httpClient->request(
            'GET',
            uri("user")->asString() . '/' . $id
        );
        return json_decode(
            strval(
                $request->getBody()
            ),
            true
        );
    }

    public function getUserIdByToken($token)
    {
        $request = $this->httpClient->request(
            'GET',
            uri("user")->asString() . '/CurrentId?user_token=' . $token
        );
        $result = json_decode(
            strval(
                $request->getBody()
            ),
            true
        );
        return $result['user_id'];
    }

    public function getUserIdByLogin($login)
    {
        $filters = new Filters(
            new EqualTo(
                new Property('login'),
                new StringValue(strval($login))
            )
        );
        $request = $this->httpClient->request(
            'GET',
            uri("user")->asString() . '/',
            [
                "query" => $filters->asKeyValue()
            ]
        );
        $result = json_decode(
            strval(
                $request->getBody()
            ),
            true
        );
        return $result['data']['user'][0]['id'];
    }
}
