<?php


namespace App\Conversations;


use App\Http\Helpers\Rest\AdmissionApi;
use App\Http\Helpers\Rest\MedCardsApi;
use App\Http\Helpers\Rest\PetsApi;
use App\Vetmanager\Api\AuthenticatedClientFactory;
use App\Vetmanager\MessageBuilder\ClientSummary\ClientSummaryMessageBuilder;
use App\Vetmanager\UserData\UserRepository\UserInterface;
use App\Vetmanager\UserData\UserRepository\UserRepository;

class ClientBriefConversation extends VetmanagerConversation
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function showBrief() {
        $client = (new AuthenticatedClientFactory($this->user()))->create();
        $petsApi = new PetsApi($client);
        $pets = $petsApi->byUserId($this->id);
        $medCardApi = new MedCardsApi($client);
        $admissionApi = new AdmissionApi($client);
        $message = "Сводка по клиенту" . PHP_EOL . PHP_EOL;
        $message .= "последний визит" . PHP_EOL;
        foreach ($pets as $pet) {
            $lastVisit = $admissionApi->getLastVisitByPetId($pet['id']);
            $message .= date('d-m-Y', strtotime($lastVisit['admission_date'])) . " - " . $pet['alias'] . PHP_EOL;
        }
        $message .= PHP_EOL . "вакцинация" . PHP_EOL;
        foreach ($pets as $pet) {
            $message .= $pet['alias'] . PHP_EOL;
            $vaccinations = $medCardApi->getVaccinationsByPetId($pet['id']);
            foreach ($vaccinations as $vaccination) {
                $message .= date('d-m-Y', strtotime($vaccination['date'])) . " " . $vaccination['name'] . PHP_EOL;
            }
        }
        $this->say($message);
        $this->endConversation();
    }

    private function user(): UserInterface
    {
        if (empty($this->user)) {
            $this->user = UserRepository::getById(
                $this->getBot()->getUser()->getId()
            );
        }
        return $this->user;
    }


    public function run()
    {
        $this->showBrief();
    }
}