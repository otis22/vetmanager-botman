<?php


namespace App\Vetmanager\MessageBuilder\Statistics;

use App\Vetmanager\MessageBuilder\MessageBuilderInterface;

class StatisticsMedCardsMessageBuilder implements MessageBuilderInterface
{
    /**
     * @var StatisticsMedCardsMessageData
     */
    private $data;

    /**
     * StatisticsMedCardsMessageBuilder constructor.
     * @param StatisticsMedCardsMessageData $data
     */
    public function __construct(StatisticsMedCardsMessageData $data)
    {
        $this->data = $data;
    }

    private function percentageText($percentage): string
    {
        if ($percentage < 0) {
            return "Это на " . abs($percentage) . "% меньше чем на предыдущей неделе.";
        } else if ($percentage == 0) {
            return "Это столько же, сколько на предыдущей неделе";
        } else {
            return "Это на " . $percentage . "% больше чем на предыдущей неделе.";
        }
    }

    public function buildMessage(): string
    {
        $dataArray = $this->data->asArray();
        return "Приветствую, {$dataArray['firstName']}. " . PHP_EOL .
            "На прошлой неделе вы создали {$dataArray['medicalCardsCount']} записей в медицинских картах." . PHP_EOL .
            $this->percentageText($dataArray['percentageDiff']);
    }

}