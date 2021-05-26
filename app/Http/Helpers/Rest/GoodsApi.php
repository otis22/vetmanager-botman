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

class GoodsApi
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
    public function goodGroups(): array
    {
        $request = $this->httpClient->request(
            'GET',
            uri("goodGroup")->asString() . '/'
        );
        return json_decode(
            strval(
                $request->getBody()
            ),
            true
        )['data']['goodGroup'];
    }

    public function goodsByGroup($groupId): array
    {
        $query = new Query(
            new Filters(
                new EqualTo(
                    new Property('group_id'),
                    new StringValue(strval($groupId))
                )
            )
        );
        $request = $this->httpClient->request(
            'GET',
            uri("good")->asString() . "/180",
            $query->asKeyValue()
        );
        return json_decode(
            strval(
                $request->getBody()
            ),
            true
        )['data']['good'];
    }
}
