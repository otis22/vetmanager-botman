<?php


namespace App\Vetmanager\Notification\Messages\Admission;


use App\Vetmanager\Notification\Messages\MessageInterface;

class AdmissionDirectedMessage implements MessageInterface
{
    /**
     * @var string
     */
    private $message = "Ожидает на прием - [%admission_date%] [%pet_alias%], [%pet_type%], [%pet_breed%], [%pet_years%], ФИО [%client_fio%] [%cell_phone%].";
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
        return str_replace(
            array_keys($this->data->asArray()),
            array_values($this->data->asArray()),
            $this->message
        );
    }
}
