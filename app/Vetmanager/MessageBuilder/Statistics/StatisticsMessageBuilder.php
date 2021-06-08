<?php


namespace App\Vetmanager\MessageBuilder\Statistics;

use App\Vetmanager\MessageBuilder\MessageBuilderInterface;

class StatisticsMessageBuilder implements MessageBuilderInterface
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function buildMessage(): string
    {
        return "Приветствую, {$this->data['firstName']}. Это статистика Vetmanager Bot." . PHP_EOL . PHP_EOL .
            "Средний рейтинг бота {$this->data['avgMark']} из 10." . PHP_EOL .
            "За неделю бот обратал {$this->data['eventsCount']} событий, из них для вас мы обработали {$this->data['eventsForUser']} событий.";
    }

}