<?php
/**
 * Created by PhpStorm.
 * User: danilyer
 * Date: 22.03.21
 * Time: 9:43
 */

namespace App\Vetmanager\MessageBuilder\Timesheet;


use App\Exceptions\VmEmptyScheduleException;
use App\Http\Helpers\Rest\SchedulesApi;
use App\Vetmanager\MessageBuilder\MessageBuilderInterface;

class TimesheetMessageBuilder implements MessageBuilderInterface
{
    /**
     * @var array
     */
    private $timesheets;

    /**
     * @var SchedulesApi
     */
    private $api;

    /**
     * AdmissionMessageBuilder constructor.
     */
    public function __construct(array $timesheets, SchedulesApi $api)
    {
        $this->timesheets = $timesheets;
        $this->api = $api;
    }

    /**
     * @return string
     */
    public function buildMessage()
    {
        $message = "";
        if (empty($this->timesheets)) {
            throw new VmEmptyScheduleException("Message haven't builded cause empty timesheet");
        }
        foreach ($this->timesheets as $timesheet) {
            $date = date_format(date_create($timesheet['begin_datetime']), "d.m.Y");
            $from = date_format(date_create($timesheet['begin_datetime']), "H:i:s");
            $to = date_format(date_create($timesheet['end_datetime']), "H:i:s");
            $type = $this->api->getTypeNameById($timesheet['type']);
            $message .= $date . PHP_EOL ."$from - $to" . PHP_EOL . $type;
            $message .= PHP_EOL . PHP_EOL;
        }
        return $message;
    }
}