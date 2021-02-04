<?php

namespace Tests\BotMan;

use BotMan\Studio\Testing\BotManTester;
use Tests\TestCase;

class RoutesTest extends TestCase
{
    /**
     * @return void
     */
    public function testAuthRoute(): void
    {
        /**
         * @var BotManTester
         */

        $this->bot
            ->receives('auth')
            ->assertReply('Привет, Босс, ответьте на 3 вопроса')
            ->receives('stop');
    }
    //TODO: Does not work two tests. 
    /**
     * @return void
     */
    public function testTimesheetRoute(): void
    {
        /**
         * @var BotManTester
         */

        $this->bot
            ->receives('timesheet')
            ->assertReply('Введите количество дней')
            ->receives('stop');
    }
}
