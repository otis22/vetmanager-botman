<?php

declare(strict_types=1);

namespace App\Conversations;

use App\Http\Helpers\Rest\Clinics;
use App\Vetmanager\UserData\ClinicToken;
use App\Vetmanager\UserData\UserRepository\UserRepository;
use BotMan\BotMan\Messages\Conversations\Conversation;
use App\Http\Helpers\Rest\Users;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use GuzzleHttp\Client;
use App\Http\Helpers\Rest\Schedules;
use Otis22\VetmanagerToken\Token\Concrete;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use App\Vetmanager\UserData\ClinicUrl;
use function Otis22\VetmanagerUrl\url;

final class TimesheetConversation extends Conversation
{

    public function saySchedule(): void
    {
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
        $schedules = new Schedules($client);
        $daysCount = 7;
        $doctorId = $this->bot->userStorage()->get('doctorId');
        $clinicId = $this->bot->userStorage()->get('clinicId');
        $timesheets = $schedules->byIntervalInDays($daysCount, $doctorId, $clinicId)['data']['timesheet'];
        if (empty($timesheets)) {
            $this->say("Нет данных");
        }
        foreach ($timesheets as $timesheet) {
            $date = date_format(date_create($timesheet['begin_datetime']), "d.m.Y");
            $from = date_format(date_create($timesheet['begin_datetime']), "H:i:s");
            $to = date_format(date_create($timesheet['end_datetime']), "H:i:s");
            $type = $schedules->getTypeNameById($timesheet['type']);
            $this->say($date . PHP_EOL ."$from - $to" . PHP_EOL . $type);
        }
    }

    private function askClinicId()
    {
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
        $clinics = (new Clinics($client))->all()['data']['clinics'];
        if (count($clinics) == 1) {
            return $this->askDoctorId();
        }

        foreach ($clinics as $clinic) {
            $text = $clinic['title'];
            $buttons[] = Button::create($text)->value($clinic['id']);
        }
        $question = Question::create('Выберите клинику')
            ->callbackId('select_clinic')
            ->addButtons($buttons);

        $this->ask($question, function (Answer $answer) {
            $clinicId = $answer->getText();
            $this->getBot()->userStorage()->save(compact('clinicId'));
            try {
                if (!is_numeric($clinicId)) {
                    throw new \Exception("Ошибка. Проверьте введенные данные!");
                }
                return $this->askDoctorId($clinicId);
            } catch (\Exception $e) {
                $this->say($e->getMessage());
            }
        });
    }

    private function askDoctorId() {
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

        $currentUserId = $user->getVmUserId();
        $buttons[] = Button::create('Мой график')->value($currentUserId);
        $users = new Users($client);
        foreach ($users->allActive()['data']['user'] as $user) {
            if ($user['id'] != $currentUserId) {
                $text = $user['first_name'] . " " . $user['last_name'] . " " . $user['login'];
                $buttons[] = Button::create($text)->value($user['id']);
            }
        }

        $question = Question::create('Выберите врача')
            ->callbackId('select_doctor')
            ->addButtons($buttons);

        $this->ask($question, function (Answer $answer) {
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
                $users = new Users($client);
                $doctorId = intval($answer->getValue());
                $this->say("Результаты для " . $users->byId($doctorId)['data']['user']['login']);
                try {
                    if (!is_numeric($doctorId)) {
                        throw new \Exception("Ошибка. Проверьте введенные данные!");
                    }
                    $this->bot->userStorage()->save(compact('doctorId'));
                    return $this->saySchedule();
                } catch (\Exception $e) {
                    $this->say($e->getMessage());
                    return $this->askDoctorId();
                }
            }
        });

    }
    /**
     * @param IncomingMessage $message
     * @return bool
     */
    public function stopsConversation(IncomingMessage $message): bool
    {
        if ($message->getText() == 'stop') {
            return true;
        }

        return false;
    }
    public function run()
    {
        $this->askClinicId();
    }
}
