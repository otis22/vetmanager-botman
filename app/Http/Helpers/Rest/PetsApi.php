<?php

namespace App\Http\Helpers\Rest;

use GuzzleHttp\Client;

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
}
