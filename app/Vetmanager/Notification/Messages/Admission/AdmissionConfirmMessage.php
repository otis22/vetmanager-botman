<?php


namespace App\Vetmanager\Notification\Messages\Admission;


use App\Vetmanager\Notification\Messages\MessageInterface;

class AdmissionConfirmMessage implements MessageInterface
{
    /**
     * @var string
     */
    private $message = "Прием подтвержден - [%admission_date%] [%pet_alias%], [%pet_type%], [%pet_breed%], Возраст: [%pet_years%], ФИО [%client_fio%].";
    /**
     * @var array
     */
    private $data;

    /**
     * AdmissionAddMessage constructor.
     * @param $data
     */
    public function __construct(AdmissionMessageData $data)
    {
        $this->data = $data;
    }

    public function asString(): string
    {
        return str_replace(array_keys($this->data->asArray()), array_values($this->data->asArray()), $this->message);
    }
}