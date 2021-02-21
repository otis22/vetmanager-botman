<?php

namespace Tests\Unit\Vetmanager;

use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Tests\TestCase;
use App\Vetmanager\MainMenu;

class MainMenuTest extends TestCase
{
    public function testMainMenuReturnIsAQuestion(): void
    {
        $this->assertInstanceOf(
            Question::class,
            (
                new MainMenu(
                    [Question::class, 'create'],
                    [Button::class, 'create'],
                    true
                )
            )->asQuestion()
        );
    }

    public function testMainMenuExistAuthAction(): void
    {
        $question = (
            new MainMenu(
                [Question::class, 'create'],
                [Button::class, 'create'],
                true
            )
        )->asQuestion();
        $this->assertTrue(
            str_contains(json_encode($question->toArray()), 'auth')
        );
    }
}
