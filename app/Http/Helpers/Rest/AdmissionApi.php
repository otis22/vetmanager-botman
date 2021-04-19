<?php

namespace App\Http\Helpers\Rest;

use App\Exceptions\VmEmptyAdmissionsException;
use GuzzleHttp\Client;
use Otis22\VetmanagerRestApi\Query\Filter\MoreThan;
use Otis22\VetmanagerRestApi\Model\Property;
use Otis22\VetmanagerRestApi\Query\Filter\Value\StringValue;
use Otis22\VetmanagerRestApi\Query\Filter\EqualTo;
use Otis22\VetmanagerRestApi\Query\Filters;

use Otis22\VetmanagerRestApi\Query\Sort\AscBy;
use Otis22\VetmanagerRestApi\Query\Sorts;
use function Otis22\VetmanagerRestApi\uri;

class AdmissionApi
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
        $sorts = new Sorts(
            new AscBy(
                new Property('admission_date')
            )
        );
        $request = $this->httpClient->request(
            'GET',
            uri("admission")->asString(),
            ["query" => array_merge($filters->asKeyValue(), $sorts->asKeyValue())]
        );
        $result = json_decode(
            strval(
                $request->getBody()
            ),
            true
        )['data']['admission'];
        if (empty($result)) {
            throw new VmEmptyAdmissionsException("Haven't planned admissions");
        }
        return $result;
    }
}
