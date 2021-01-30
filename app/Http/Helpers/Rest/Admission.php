<?php

namespace App\Http\Helpers\Rest;

use GuzzleHttp\Client;
use Otis22\VetmanagerRestApi\Query\Filter\MoreThan;
use Otis22\VetmanagerRestApi\Model\Property;
use Otis22\VetmanagerRestApi\Query\Filter\Value\StringValue;
use Otis22\VetmanagerRestApi\Query\Filter\EqualTo;
use Otis22\VetmanagerRestApi\Query\Filters;

use function Otis22\VetmanagerRestApi\uri;

class Admission
{
    /**
     * @var Client
     */
    private $httpClient;

    /**
     * Admission constructor.
     * @param Client $httpClient
     */
    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getByUserId($id)
    {
        $filteringParams[] = new MoreThan(
            new Property('admission_date'),
            new StringValue(date('Y-m-d H:i:s'))
        );
        $filteringParams[] = new EqualTo(
            new Property('user_id'),
            new StringValue(strval($id))
        );
        $filters = new Filters(...$filteringParams);
        $request = $this->httpClient->request(
            'GET',
            uri("admission")->asString(),
            ["query" => $filters->asKeyValue()]
        );
        $result = json_decode(
            strval(
                $request->getBody()
            ),
            true
        );
        return $result;
    }
}
