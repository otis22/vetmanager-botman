<?php

declare(strict_types=1);

namespace App\Conversations;

use App\Http\Helpers\Rest\ComboManual;
use App\Vetmanager\UserData\ClinicToken;
use App\Vetmanager\UserData\ClinicUrl;
use App\Vetmanager\UserData\UserRepository\UserRepository;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use GuzzleHttp\Client;
use Otis22\VetmanagerToken\Token\Concrete;
use function Otis22\VetmanagerUrl\url;

final class NotificationConversation extends Conversation
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

        $this->bot->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $user = UserRepository::getById($this->getBot()->getUser()->getId());
                $token = new Concrete(
                    (
                    new ClinicToken(
                        $user
                    )
                    )->asString()
                );
                $baseUri = (
                new ClinicUrl(
                    function (string $domain) : string {
                        return url($domain)->asString();
                    },
                    $user
                )
                )->asString();
                $client = new Client(
                    [
                        'base_uri' => $baseUri,
                        'headers' => ['X-USER-TOKEN' => $token->asString(), 'X-APP-NAME' => config('app.name')]
                    ]
                );

                $comboManual = new ComboManual($client);
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
            }
        });
    }

    public function run()
    {
        $this->manageNotification();
    }
}
