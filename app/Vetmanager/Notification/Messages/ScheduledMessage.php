<?php


namespace App\Vetmanager\Notification\Messages;


class ScheduledMessage implements MessageInterface
{
    /**
     * @var string
     */
    private $timesheetMessage;

    /**
     * @var string
     */
    private $admissionMessage;

    /**
     * ScheduledMessage constructor.
     * @param $timesheetMessage string
     * @param $admissionMessage string
     */
    public function __construct($timesheetMessage, $admissionMessage)
    {
        $this->timesheetMessage = $timesheetMessage;
        $this->admissionMessage = $admissionMessage;
    }

    public function asString(): string
    {
        $message = "Расписание на завтра: " . PHP_EOL . $this->timesheetMessage . PHP_EOL;
        $message .= "Запланированные приемы: " . PHP_EOL . $this->admissionMessage;
        return $message;
    }

}