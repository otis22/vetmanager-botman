<?php


namespace App\Vetmanager\Notification\Messages\Admission;


use App\Http\Helpers\Rest\PetsApi;
use App\Vetmanager\Api\AuthenticatedClientFactory;
use App\Vetmanager\UserData\UserRepository\UserRepository;

class AdmissionMessageDataFactory
{
    private $user;
    private $input;

    public function __construct($user, $input)
    {
        $this->user = $user;
        $this->input = $input;
    }

    public function create()
    {
        $clientFactory = new AuthenticatedClientFactory(UserRepository::getById($this->user[0]->chat_id));
        $client = $clientFactory->create();
        $pets = new PetsApi($client);
        $pet = $pets->byId(intval($this->input['data']['patient_id']))['data']['pet'];
        return new AdmissionMessageData($pet, $this->input);
    }
}