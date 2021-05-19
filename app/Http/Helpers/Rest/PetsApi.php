<?php

namespace App\Http\Helpers\Rest;

use GuzzleHttp\Client;

use Otis22\VetmanagerRestApi\Model\Property;
use Otis22\VetmanagerRestApi\Query\Filter\EqualTo;
use Otis22\VetmanagerRestApi\Query\Filter\Value\StringValue;
use Otis22\VetmanagerRestApi\Query\Filters;
use Otis22\VetmanagerRestApi\Query\Query;
use Otis22\VetmanagerRestApi\Query\Sort\AscBy;
use Otis22\VetmanagerRestApi\Query\Sorts;
use function Otis22\VetmanagerRestApi\uri;

class PetsApi
{
    /**
     * @var Client
     */
    private $httpClient;

    /**
     * Pets constructor.
     */
    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
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
            uri("pet")->asString() . '/' . $id
        );
        return json_decode(
            strval(
                $request->getBody()
            ),
            true
        );
    }

    public function byUserId(int $id): array
    {
        $query = new Query(
            new Filters(
                new EqualTo(
                    new Property('owner_id'),
                    new StringValue(strval($id))
                )
            )
        );

        $request = $this->httpClient->request(
            'GET',
            uri("pet")->asString() . '/',
            ["query" => $query->asKeyValue()]
        );
        return json_decode(
            strval(
                $request->getBody()
            ),
            true
        )['data']['pet'];
    }
}
