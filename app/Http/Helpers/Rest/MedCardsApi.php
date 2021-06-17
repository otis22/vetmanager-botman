<?php


namespace App\Http\Helpers\Rest;


use GuzzleHttp\Client;
use Otis22\VetmanagerRestApi\Model\Property;
use Otis22\VetmanagerRestApi\Query\Filter\EqualTo;
use Otis22\VetmanagerRestApi\Query\Filter\LessOrEqualThan;
use Otis22\VetmanagerRestApi\Query\Filter\MoreOrEqualThan;
use Otis22\VetmanagerRestApi\Query\Filter\Value\StringValue;
use Otis22\VetmanagerRestApi\Query\Filters;
use Otis22\VetmanagerRestApi\Query\Query;
use Otis22\VetmanagerRestApi\Query\Sort\AscBy;
use Otis22\VetmanagerRestApi\Query\Sorts;
use function Otis22\VetmanagerRestApi\uri;

class MedCardsApi
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
            uri("medicalCards")->asString(),
            ["query" => $query->asKeyValue()]
        );
        $result = json_decode(
            strval(
                $request->getBody()
            ),
            true
        )['data']['medicalCards'];
        return $result;
    }

    public function getByPetId($id)
    {
        $query = new Query(
            new Filters(
                new EqualTo(
                    new Property('patient_id'),
                    new StringValue(strval($id))
                )
            )
        );
        return $this->sendRequest($query, "GET");
    }

    public function getVaccinationsByPetId($id)
    {
        $request = $this->httpClient->request(
            'GET',
            uri("medicalCards")->asString() . "/Vaccinations?pet_id=" . $id
        );
        $result = json_decode(
            strval(
                $request->getBody()
            ),
            true
        );
        return $result['data']['medicalcards'];
    }

    public function lastWeekMedCards($userId)
    {
        $monday = strtotime("last monday");
        $lastWeekMonday = date('W', $monday)==date('W') ? $monday-7*86400 : $monday;
        $lastWeekSunday = strtotime(date("Y-m-d",$lastWeekMonday)." +6 days");
        return $this->medCardsByDateRangeAndUserId($lastWeekMonday, $lastWeekSunday, $userId);
    }

    public function medCardsByDateRangeAndUserId($startDate, $endDate, $userId)
    {
        $query = new Query(
            new Filters(
                new MoreOrEqualThan(
                    new Property('date_create'),
                    new StringValue(date("Y-m-d", $startDate))
                ),
                new LessOrEqualThan(
                    new Property('date_create'),
                    new StringValue(date("Y-m-d", $endDate))
                ),
                new EqualTo(
                    new Property("doctor_id"),
                    new StringValue(strval($userId))
                )
            )
        );
        return $this->sendRequest($query, "GET");
    }
}
