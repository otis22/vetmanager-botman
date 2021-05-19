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
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class AdmissionMessageBuilder implements MessageBuilderInterface
{
    /**
     * @var array
     */
    private $admission;

    /**
     * AdmissionMessageBuilder constructor.
     */
    public function __construct(array $admission)
    {
        $this->admission = $admission;
    }

    /**
     * @return Question
     */
    public function buildMessage()
    {
        $message = "";
        $message .= $this->admission['admission_date'] . PHP_EOL;
        if (isset($this->admission['client'])) {
            $message .= "Клиент: ";
            $message .= $this->admission['client']['last_name'] . " " . $this->admission['client']['first_name'] . PHP_EOL;
            if ($this->admission['client']['cell_phone']) {
                $message .= "Телефон: ";
                $message .= $this->admission['client']['cell_phone'] . PHP_EOL;
            }
        } else {
            $message .= "Клиент: <пусто>";
        }
        if (isset($this->admission['pet'])) {
            $message .= "Кличка питомца: " . $this->admission['pet']['alias'] . PHP_EOL;
            $message .= "Тип: " . $this->admission['pet']['pet_type_data']['title'] . PHP_EOL;
            $message .= "Порода: " . $this->admission['pet']['breed_data']['title'];
        }
        $message .= PHP_EOL . PHP_EOL;
        return Question::create($message)
            ->addButtons([Button::create("Сводка по клиенту")->value('clientBrief ' . $this->admission['client']['id'])]);
    }
}
