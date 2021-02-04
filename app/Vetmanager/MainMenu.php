<?php

declare(strict_types=1);

namespace App\Vetmanager;

use BotMan\BotMan\Messages\Outgoing\Question;

final class MainMenu
{
    /**
     * @var string[]
     */
    private $commandsConfig = [
        'auth' => 'Авторизация(Необходима для работы бота)',
        'timesheet' => 'График работы сотрудников',
        'admissions' =>  'Мои запланированные визиты',
        'notification' => 'Управление уведомлениями'
    ];
    /**
     * @var callable(string $questionTitle): Question
     */
    private $questionFactory;
    /**
     * @var callable(string $buttonTitle): Button
     */
    private $buttonFactory;

    /**
     * MainMenu constructor.
     * @param callable $questionFactory
     * @param callable $buttonFactory
     */
    public function __construct(callable $questionFactory, callable $buttonFactory)
    {
        $this->questionFactory = $questionFactory;
        $this->buttonFactory = $buttonFactory;
    }

    /**
     * @return string[]
     */
    private function commands(): array
    {
        return array_keys($this->commandsConfig);
    }

    /**
     * @return string[]
     */
    private function titles(): array
    {
        return array_values($this->commandsConfig);
    }

    public function asQuestion(): Question
    {
        $questionFactory = $this->questionFactory;
        $buttonFactory = $this->buttonFactory;

        return $questionFactory('Что мне сделать?')
            ->addButtons(
                array_map(
                    function ($command, $title) use ($buttonFactory) {
                        return $buttonFactory($title)->value($command);
                    },
                    $this->commands(),
                    $this->titles()
                )
            );
    }
}
