<?php


namespace App\Http\Helpers;

use ElegantBro\Interfaces\Stringify;

class VmEvent implements Stringify {
    /**
     * @var string
     */
    private $event;
    /**
     * @var string[]
     */
    private $eventList = [
        'invoiceRollback' => "Откатили счёт",
    ];

    public function __construct($event)
    {
        $this->event = $event;
    }

    public function hasTranslation(): bool
    {
        return array_key_exists($this->event, $this->eventList);
    }

    public function asString(): string
    {
        if ($this->hasTranslation()) {
            return $this->eventList[$this->event];
        } else {
            return $this->event;
        }
    }

}