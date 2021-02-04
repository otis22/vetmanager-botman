<?php

declare(strict_types=1);

namespace App\Conversations;

use App\Http\Helpers\Rest\ComboManual;
use App\Vetmanager\UserData\ClinicUrl;
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
                $token = new Concrete($this->getBot()->userStorage()->get('clinicUserToken'));
                $baseUri = (
                new ClinicUrl(
                    $this->getBot(),
                    function (string $domain) : string {
                        return url($domain)->asString();
                    }
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
                    $result = $comboManual->enableNotification($this->getBot()->userStorage()->get("clinicDomain"));
                    if ($result['success']) {
                        $this->say("Уведомления включены.");
                    } else {
                        $this->say("Ошибка: " . $result['message']);
                    }
                } else {
                    $result = $comboManual->disableNotification($this->getBot()->userStorage()->get("clinicDomain"));

                    if ($result['success']) {
                        $this->say("Уведомления выключены.");
                    } else {
                        $this->say("Ошибка: " . $result['message']);
                    }
                }
            }
        });
    }

    public function run()
    {
        $this->manageNotification();
    }
}
