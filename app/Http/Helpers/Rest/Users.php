<?php

namespace App\Http\Helpers\Rest;

use GuzzleHttp\Client;
use Otis22\VetmanagerRestApi\Model\Property;
use Otis22\VetmanagerRestApi\Query\Filter\EqualTo;
use Otis22\VetmanagerRestApi\Query\Filter\Value\StringValue;
use Otis22\VetmanagerRestApi\Query\Filters;

use function Otis22\VetmanagerRestApi\uri;

class Users
{
    /**
     * @var Client
     */
    private $httpClient;

    /**
     * Users constructor.
     * @param Client $httpClient
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
        $result = json_decode(
            strval(
                $request->getBody()
            ),
            true
        );
        return $result;
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
        $result = json_decode(
            strval(
                $request->getBody()
            ),
            true
        );
        return $result;
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
        $filteringParams[] = new EqualTo(
            new Property('login'),
            new StringValue(strval($login))
        );
        $filters = new Filters(...$filteringParams);
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
