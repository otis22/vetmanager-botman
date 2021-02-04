<?php

namespace App\Http\Helpers\Rest;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\URL;

use Otis22\VetmanagerRestApi\Model\Property;
use Otis22\VetmanagerRestApi\Query\Filter\EqualTo;
use Otis22\VetmanagerRestApi\Query\Filter\Value\StringValue;
use Otis22\VetmanagerRestApi\Query\Filters;
use PHPUnit\Runner\Exception;
use function Otis22\VetmanagerRestApi\uri;

class ComboManual
{
    /**
     * @var Client
     */
    private $httpClient;

    /**
     * ComboManual constructor.
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
            uri("comboManualItem")->asString()
        );
        $result = json_decode(
            strval(
                $request->getBody()
            ),
            true
        );
        return $result;
    }

    public function enableNotification($domainName)
    {
        $id = $this->getExistHookId();
        if ($id) {
            return ['success' => false, 'message' => "Уведомления уже работают."];
        }
        $request = $this->httpClient->request(
            'POST',
            uri("comboManualItem")->asString(),
            [
                "body" => json_encode([
                    'title' => 'Botman',
                    'value' => URL::to('/') . "/event/" . $domainName,
                    'combo_manual_id' => 11
                ])
            ]
        );
        $result = json_decode(
            strval(
                $request->getBody()
            ),
            true
        );
        return $result;
    }

    public function disableNotification(): array
    {
        $id = $this->getExistHookId();
        if (!$id) {
            return ['success' => false, 'message' => "Уведомления и так выключены."];
        }
        if ($id)
        $request = $this->httpClient->request(
            'DELETE',
            uri("comboManualItem")->asString() . "/" . $id
        );
        $result = json_decode(
            strval(
                $request->getBody()
            ),
            true
        );
        return $result;
    }

    public function getExistHookId()
    {
        $filteringParams[] = new EqualTo(
            new Property('title'),
            new StringValue(strval('Botman'))
        );
        $filters = new Filters(...$filteringParams);
        $request = $this->httpClient->request(
            'GET',
            uri("comboManualItem")->asString() . '/',
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
        if (!empty($result['data']['comboManualItem'])) {
            return $result['data']['comboManualItem'][0]['id'];
        }
        return 0;
    }

}
