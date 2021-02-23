<?php


namespace Tests\Unit\Vetmanager\Notification\Messages;

use App\Vetmanager\Notification\Messages\RollbackMessage;
use PHPUnit\Framework\TestCase;

class RollbackMessageTest extends TestCase
{
    public function testRollbackMessage()
    {
        $message = new RollbackMessage([
            'data' => [
                'id' => 666,
                'amount' => 228
            ]
        ]);
        $this->assertEquals($message->asString(), "Откатили счет 666 на сумму 228");
    }
}