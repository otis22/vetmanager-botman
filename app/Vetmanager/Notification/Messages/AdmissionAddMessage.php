<?php


namespace App\Vetmanager\Notification\Messages;


use ElegantBro\Interfaces\Stringify;

class AdmissionAddMessage implements MessageInterface, Stringify
{
    /**
     * @var string
     */
    private $message = "Создан прием - [%admission_date%] [%pet_alias%], [%pet_type%], [%pet_breed%], [%pet_years%], ФИО [%client_fio%].";
    /**
     * @var array
     */
    private $data;

    /**
     * AdmissionAddMessage constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function asString(): string
    {
        $id = $this->data['data']['id'];
        $summa = $this->data['data']['amount'];
        $result = str_replace('{{id}}', $id, $this->message);
        $result = str_replace('{{summa}}', intval($summa), $result);
        return $result;
    }
}