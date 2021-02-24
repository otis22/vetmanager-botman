<?php


namespace App\Vetmanager\Notification\Messages;


use ElegantBro\Interfaces\Stringify;

class RollbackMessage implements MessageInterface, Stringify
{
    /**
     * @var string
     */
    private $message = "Откатили счет {{id}} на сумму {{summa}}";
    /**
     * @var array
     */
    private $data;

    /**
     * RollbackMessage constructor.
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