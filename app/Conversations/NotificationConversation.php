<?php

declare(strict_types=1);

namespace App\Conversations;

use App\Http\Helpers\Rest\ComboManual;
use App\Vetmanager\Api\AuthenticatedClientFactory;
use App\Vetmanager\UserData\UserRepository\UserRepository;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;

final class NotificationConversation extends VetmanagerConversation
{
    public function manageNotification()
    {
        $question = Question::create('Настройка уведомлений')
            ->callbackId('notification_status')
            ->addButtons(
                [
                    Button::create("Вкл.")->value("on"),
                    Button::create("Выкл.")->value("off")
                ]
            );

        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $user = UserRepository::getById($this->getBot()->getUser()->getId());
                $clientFactory = new AuthenticatedClientFactory($user);

                $comboManual = new ComboManual($clientFactory->create());
                if ($answer->getValue() == "on")
                {
                    $user->enableNotifications();
                    $comboManual->addNotificationRoute($user->getDomain());
                    $this->say("Уведомления включены.");
                } else {
                    $user->disableNotifications();
                    $this->say("Уведомления выключены.");
                }
                UserRepository::save($user);
                $this->endConversation();
            }
        });

    }

    public function run()
    {
        $this->manageNotification();
    }
}
