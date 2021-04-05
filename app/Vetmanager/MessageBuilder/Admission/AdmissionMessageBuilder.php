<?php
/**
 * Created by PhpStorm.
 * User: danilyer
 * Date: 22.03.21
 * Time: 9:43
 */

namespace App\Vetmanager\MessageBuilder\Admission;


use App\Exceptions\VmEmptyAdmissionsException;
use App\Vetmanager\MessageBuilder\MessageBuilderInterface;

class AdmissionMessageBuilder implements MessageBuilderInterface
{
    /**
     * @var array
     */
    private $admissions;

    /**
     * AdmissionMessageBuilder constructor.
     * @param array $admissions
     */
    public function __construct(array $admissions)
    {
        $this->admissions = $admissions;
    }

    /**
     * @return string
     */
    public function buildMessage()
    {
        $message = "";
        if (empty($this->admissions)) {
            throw new VmEmptyAdmissionsException("Message haven't builded cause empty admissions");
        }
        foreach ($this->admissions as $concrete) {
            $message = $concrete['admission_date'] .PHP_EOL;
            if (isset($concrete['client'])) {
                $message .= "Клиент: ";
                $message .= $concrete['client']['last_name'] . " " . $concrete['client']['first_name'] . PHP_EOL;
            } else {
                $message .= "Клиент: <пусто>";
            }
            if (isset($concrete['pet'])) {
                $message .= "Кличка питомца: " . $concrete['pet']['alias'] . PHP_EOL;
                $message .= "Тип: " . $concrete['pet']['pet_type_data']['title'] . PHP_EOL;
                $message .= "Порода: " . $concrete['pet']['breed_data']['title'];
            }
            $message .= PHP_EOL . PHP_EOL;
        }
        return $message;
    }
}