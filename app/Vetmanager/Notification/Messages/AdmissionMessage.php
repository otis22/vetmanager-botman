<?php


namespace App\Vetmanager\Notification\Messages;


use App\Http\Helpers\Rest\Pets;
use GuzzleHttp\Client;

class AdmissionMessage implements MessageInterface
{
    /**
     * @var string
     */
    private $messages = [
        'add' => "Создан прием - [%admission_date%] [%pet_alias%], [%pet_type%], [%pet_breed%], Возраст: [%pet_years%], ФИО [%client_fio%].",
        'changed' => "Прием изменен - [%admission_date%] [%pet_alias%], [%pet_type%], [%pet_breed%], Возраст: [%pet_years%], ФИО [%client_fio%].",
        'confirmed' => "Прием подтвержден - [%admission_date%] [%pet_alias%], [%pet_type%], [%pet_breed%], Возраст: [%pet_years%], ФИО [%client_fio%]."
    ];
    /**
     * @var array
     */
    private $data;

    /**
     * @var Client
     */
    private $client;

    /**
     * AdmissionAddMessage constructor.
     * @param $data
     */
    public function __construct($data, Client $client)
    {
        $this->data = $data;
        $this->client = $client;
    }

    public function asString(): string
    {
        switch ($this->data['name']) {
            case 'admissionAdd':
                $message = $this->messages['add'];
            break;
            case 'admissionEdit':
                $message = $this->messages['changed'];
            break;
            case 'admissionConfirmed':
                $message = $this->messages['confirmed'];
            break;
        }
        $pets = new Pets($this->client);
        $pet = $pets->byId(intval($this->data['data']['patient_id']))['data']['pet'];
        $result = str_replace('[%admission_date%]', $this->data['data']['admission_date'], $message);
        $result = str_replace('[%pet_alias%]', $pet['alias'], $result);
        $result = str_replace('[%pet_type%]', $pet['type']['title'], $result);
        $result = str_replace('[%pet_breed%]', $pet['breed']['title'], $result);

        $now = new \DateTime();
        $birthday = new \DateTime($pet['birthday']);
        $interval = $now->diff($birthday);
        $pet_years = $interval->format('%y');
        $result = str_replace('[%pet_years%]', $pet_years, $result);
        $fio = $pet['owner']['last_name'] . " " . $pet['owner']['first_name'] . " " . $pet['owner']['middle_name'];
        $result = str_replace('[%client_fio%]', $fio, $result);
        return $result;
    }
}