<?php

namespace Tests\BotMan;

use BotMan\Studio\Testing\BotManTester;
use Illuminate\Foundation\Inspiring;
use Tests\TestCase;

class AuthConversationTest extends TestCase
{
    /**
     * A conversation test example.
     *
     * @return void
     */
    public function testInvalidDomain(): void
    {
        /**
         * @var BotManTester
         */

        $this->bot()
            ->receives('start')
            ->assertReply('Привет, Босс, ответьте на 3 вопроса')
            ->assertReply('Введите доменное имя или адрес программы. Пример: myclinic или https://myclinic.vetmanager.ru')
            ->receivesInteractiveMessage('myclinic')
            ->assertReply('myclinic');
    }

    private function bot(): BotManTester
    {
        return $this->bot;
    }
}
