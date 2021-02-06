<?php

namespace Tests\BotMan;

use App\Conversations\TimesheetConversation;
use Tests\BotManTestCase;

class TimesheetConversationTest extends BotManTestCase
{
    public function testFirstMessage(): void
    {
        $this->botman->hears('message', function ($bot) {
            $bot->startConversation(new TimesheetConversation());
        });

        $this->tester->receives('message')
            ->assertReply('Введите количество дней');
    }
}
