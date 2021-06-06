<?php


namespace App\Vetmanager\Notification\Messages\Admission;


use App\Vetmanager\Notification\Messages\MessageDataInterface;

class AdmissionMessageData implements MessageDataInterface
{
    private $admissionData;
    private $petData;

    public function __construct($petData, $admissionData)
    {
        $this->petData = $petData;
        $this->admissionData = $admissionData;
    }

    public function asArray(): array
    {
        $now = new \DateTime();
        $birthday = new \DateTime($this->petData['birthday']);
        $interval = $now->diff($birthday);
        $pet_years = $interval->format('%y');
        if (is_null($this->petData['birthday'])) {
            $pet_age = "";
        } elseif ($pet_years < 1) {
            $pet_age = "Возраст: " . $interval->format('%m') . " " . "месяцев";
        } else {
            $pet_age = "Возраст: " . $pet_years . " лет";
        }
        $fio = $this->petData['owner']['last_name'] . " " . $this->petData['owner']['first_name'] . " " . $this->petData['owner']['middle_name'];
        $cell_phone = $this->petData['owner']['cell_phone'];
        return [
            '[%admission_date%]' => $this->admissionData['data']['admission_date'],
            '[%pet_alias%]' => $this->petData['alias'],
            '[%pet_type%]' => $this->petData['type']['title'],
            '[%pet_breed%]' => $this->petData['breed']['title'],
            '[%pet_years%]' => $pet_age,
            '[%client_fio%]' => $fio,
            '[%cell_phone%]' => $cell_phone
        ];
    }
}