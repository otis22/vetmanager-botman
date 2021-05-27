<?php

declare(strict_types=1);

namespace App\Conversations;

use App\Exceptions\VmEmptyAdmissionsException;
use App\Http\Helpers\Rest\AdmissionApi;
use App\Vetmanager\Api\AuthenticatedClientFactory;
use App\Vetmanager\MessageBuilder\Admission\AdmissionMessageBuilder;
use App\Vetmanager\UserData\UserRepository\UserInterface;
use App\Vetmanager\UserData\UserRepository\UserRepository;
use App\Http\Helpers\Rest\UsersApi;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;


final class AdmissionConversation extends VetmanagerConversation
{
    /**
     * @var UserInterface
     */

    private function getDatesButtons(): array {
        $buttons = [];
        for ($day=0; $day < 7; $day++) {
            $date = date('Y-m-d', strtotime('+ ' . $day . ' days'));
            $buttons[] = Button::create($date)->value($date);
        }
        return $buttons;
    }

    public function askDate()
    {
        $question = Question::create('Выберите день')
            ->callbackId('select_day')
            ->addButtons($this->getDatesButtons());

        $this->ask($question, function (Answer $answer) {
            $admission_date = $answer->getText();
            if (strtotime($admission_date)) {
                $this->getBot()->userStorage()->save(compact('admission_date'));
                $this->showAdmissions();
            } else {
                $this->say("Ошибка ввода");
                $this->askDate();
            }
        });
    }

    public function showAdmissions()
    {
        try {
            $client = (new AuthenticatedClientFactory($this->user()))->create();
            $users = new UsersApi($client);
            $currentUserLogin = $this->getBot()->userStorage()->get('userLogin');
            $currentUserId = $users->getUserIdByLogin($currentUserLogin);
            $date = $this->getBot()->userStorage()->get('admission_date');
            $admissions = (new AdmissionApi($client))->getByUserIdAndDate($currentUserId, $date);
            foreach ($admissions as $admission) {
                $messageBuilder = new AdmissionMessageBuilder($admission);
                $message = $messageBuilder->buildMessage();
                $this->say($message);
            }
        }
        catch (VmEmptyAdmissionsException $exception) {
            $this->say("У вас нет запланированных приёмов.");
        }
        catch (\Throwable $exception) {
            $this->say("Ошибка: " . $exception->getMessage());
        }
        $this->endConversation();
    }

    public function run()
    {
        $this->askDate();
    }
}
