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
        'auth' => 'Сменить пользователя',
        'timesheet' => 'График работы сотрудников',
        'admissions' =>  'Мои запланированные визиты',
        'notification' => 'Управление уведомлениями',
        'review' => 'Оцените меня',
        'stats' => "Статистика Бота",
        'pricelist' => "Прайс-лист",
        'service' => "Сервисы для вашего сайта"
    ];

    /**
     * @var string[]
     */
    private $unauthCommandsConfig = [
        'auth' => 'Авторизация(Необходима для работы бота)',
        'stats' => 'Статистика Бота'
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
     * @var bool
     */
    private $isAuthorized;

    /**
     * MainMenu constructor.
     */
    public function __construct(callable $questionFactory, callable $buttonFactory, bool $isAuthorized)
    {
        $this->questionFactory = $questionFactory;
        $this->buttonFactory = $buttonFactory;
        $this->isAuthorized = $isAuthorized;
    }

    /**
     * @return string[]
     */
    private function commands(): array
    {
        $config = $this->isAuthorized ? $this->commandsConfig : $this->unauthCommandsConfig;
        return array_keys($config);
    }

    /**
     * @return string[]
     */
    private function titles(): array
    {
        $config = $this->isAuthorized ? $this->commandsConfig : $this->unauthCommandsConfig;
        return array_values($config);
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
