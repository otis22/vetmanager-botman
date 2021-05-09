<?php

namespace App\Http\Helpers\Rest;

use GuzzleHttp\Client;
use Otis22\VetmanagerRestApi\Model\Property;
use Otis22\VetmanagerRestApi\Query\Filter\EqualTo;
use Otis22\VetmanagerRestApi\Query\Filter\Value\StringValue;
use Otis22\VetmanagerRestApi\Query\Filters;

use function Otis22\VetmanagerRestApi\uri;

class ClinicsApi
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
            uri("clinics")->asString()
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
            uri("clinics")->asString() . '/' . $id
        );
        return json_decode(
            strval(
                $request->getBody()
            ),
            true
        );
    }

}
