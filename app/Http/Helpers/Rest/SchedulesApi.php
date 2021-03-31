<?php

namespace App\Http\Helpers\Rest;

use App\Exceptions\VmEmptyScheduleException;
use GuzzleHttp\Client;
use Otis22\VetmanagerRestApi\Query\Filter\MoreThan;
use Otis22\VetmanagerRestApi\Query\Filter\LessThan;
use Otis22\VetmanagerRestApi\Model\Property;
use Otis22\VetmanagerRestApi\Query\Filter\Value\StringValue;
use Otis22\VetmanagerRestApi\Query\Filter\EqualTo;
use Otis22\VetmanagerRestApi\Query\Filters;

use function Otis22\VetmanagerRestApi\uri;

class SchedulesApi
{
    /**
     * @var Client
     */
    private $httpClient;

    /**
     * Schedules constructor.
     * @param Client $httpClient
     */
    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param int $days
     * @param int $doctor_id
     * @param int $clinic_id
     * @return array{success:bool}
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function byIntervalInDays($days = 7, $doctor_id = 0, $clinic_id = 0): array
    {
        $now = date("Y-m-d");
        $filteringParams[] = new MoreThan(
            new Property('begin_datetime'),
            new StringValue($now . " 00:00:00")
        );
        $filteringParams[] = new LessThan(
            new Property('end_datetime'),
            new StringValue(date('Y-m-d', intval(strtotime($now . " +" . $days . " days"))) . " 23:59:59")
        );
        if ($doctor_id) {
            $filteringParams[] = new EqualTo(
                new Property('doctor_id'),
                new StringValue(strval($doctor_id))
            );
        }
        if ($clinic_id) {
            $filteringParams[] = new EqualTo(
                new Property('clinic_id'),
                new StringValue(strval($clinic_id))
            );
        }
        $filters = new Filters(...$filteringParams);
        $request = $this->httpClient->request(
            'GET',
            uri("timesheet")->asString(),
            [
                "query" => $filters->asKeyValue()
            ]
        );
        $schedules = json_decode(
            strval(
                $request->getBody()
            ),
            true
        )['data']['timesheet'];
        if (empty($schedules)) {
            throw new VmEmptyScheduleException("Schedules is empty");
        }
        return $schedules;
    }

    public function getTypeNameById($id): string
    {
        $request = $this->httpClient->request(
            'GET',
            uri("timesheetTypes")->asString(),
            []
        );
        $result = json_decode(
            strval(
                $request->getBody()
            ),
            true
        );
        $types = $result['data']['timesheetTypes'];
        foreach ($types as $type) {
            if ($type['id'] == $id) {
                return $type['name'];
            }
        }
        return false;
    }
}
