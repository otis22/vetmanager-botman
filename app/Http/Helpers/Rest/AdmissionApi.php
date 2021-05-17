<?php

namespace App\Http\Helpers\Rest;

use App\Exceptions\VmEmptyAdmissionsException;
use GuzzleHttp\Client;
use Otis22\VetmanagerRestApi\Model\Property;
use Otis22\VetmanagerRestApi\Query\Filter\LessThan;
use Otis22\VetmanagerRestApi\Query\Filter\MoreThan;
use Otis22\VetmanagerRestApi\Query\Filter\Value\StringValue;
use Otis22\VetmanagerRestApi\Query\Filter\EqualTo;
use Otis22\VetmanagerRestApi\Query\Filters;
use Otis22\VetmanagerRestApi\Query\Query;
use Otis22\VetmanagerRestApi\Query\Sort\AscBy;
use Otis22\VetmanagerRestApi\Query\Sort\DescBy;
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
     */
    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    private function sendRequest(Query $query, $method)
    {
        $request = $this->httpClient->request(
            $method,
            uri("admission")->asString(),
            ["query" => $query->asKeyValue()]
        );
        $result = json_decode(
            strval(
                $request->getBody()
            ),
            true
        )['data']['admission'];
        if (empty($result)) {
            throw new VmEmptyAdmissionsException(json_encode($query->asKeyValue()));
        }
        return $result;
    }

    public function getByUserIdAndDate($id, $date)
    {
        $filteringParams[] = new EqualTo(
            new Property('user_id'),
            new StringValue(strval($id))
        );
        $filteringParams[] = new EqualTo(
            new Property('admission_date'),
            new StringValue(date('Y-m-d', strtotime($date)))
        );
        $query = new Query(
            new Filters(...$filteringParams),
            new Sorts(
                new AscBy(
                    new Property('admission_date')
                )
            )
        );
        return $this->sendRequest($query, "GET");
    }

    public function getByUserId($id)
    {
        $filteringParams[] = new EqualTo(
            new Property('user_id'),
            new StringValue(strval($id))
        );
        $query = new Query(
            new Filters(...$filteringParams),
            new Sorts(
                new AscBy(
                    new Property('admission_date')
                )
            )
        );
        return $this->sendRequest($query, "GET");
    }

    public function getLastVisitByPetId($id)
    {
        $filteringParams[] = new EqualTo(
            new Property('patient_id'),
            new StringValue(strval($id))
        );
        $filteringParams[] = new LessThan(
            new Property('admission_date'),
            new StringValue(date('Y-m-d H:i:s'))
        );
        $query = new Query(
            new Filters(...$filteringParams),
            new Sorts(
                new DescBy(
                    new Property('admission_date')
                )
            )
        );
        return $this->sendRequest($query, "GET")[0];
    }
}
