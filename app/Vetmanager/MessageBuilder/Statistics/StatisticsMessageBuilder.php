<?php


namespace App\Vetmanager\MessageBuilder\Statistics;

use App\Vetmanager\MessageBuilder\MessageBuilderInterface;

class StatisticsMessageBuilder implements MessageBuilderInterface
{
    /**
     * @var StatisticsMessageData
     */
    private $data;

    /**
     * StatisticsMessageBuilder constructor.
     * @param StatisticsMessageData $data
     */
    public function __construct(StatisticsMessageData $data)
    {
        $this->data = $data;
    }

    public function buildMessage(): string
    {
        $dataArray = $this->data->asArray();
        return "Приветствую, {$dataArray['firstName']}. Это статистика Vetmanager Bot." . PHP_EOL . PHP_EOL .
            "Средний рейтинг бота {$dataArray['avgMark']} из 10." . PHP_EOL .
            "За неделю бот обратал {$dataArray['eventsCount']} событий, из них для вас мы обработали {$dataArray['eventsForUser']} событий.";
    }

}