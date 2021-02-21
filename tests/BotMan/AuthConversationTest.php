<?php

namespace Tests\Unit\BotMan;

use App\Conversations\AuthConversation;
use Tests\BotManTestCase;

class AuthConversationTest extends BotManTestCase
{
    public function testFirstMessage(): void
    {
        $this->botman->hears('message', function ($bot) {
            $bot->startConversation(new AuthConversation());
        });

        $this->tester->receives('message')
            ->assertReply('Привет, Босс, ответьте на 3 вопроса');
    }
}
