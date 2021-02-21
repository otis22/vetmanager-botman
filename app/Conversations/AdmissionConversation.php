<?php

declare(strict_types=1);

namespace App\Conversations;

use App\Http\Helpers\Rest\Admission;
use App\Vetmanager\Api\AuthenticatedClientFactory;
use App\Vetmanager\MainMenu;
use App\Vetmanager\UserData\UserRepository\UserInterface;
use App\Vetmanager\UserData\UserRepository\UserRepository;
use BotMan\BotMan\Messages\Conversations\Conversation;
use App\Http\Helpers\Rest\Users;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;


final class AdmissionConversation extends Conversation
{
    /**
     * @var UserInterface
     */
    private $user;

    /**
     * @return UserInterface
     */
    private function user(): UserInterface
    {
        if (empty($this->user)) {
            $this->user = UserRepository::getById(
                $this->getBot()->getUser()->getId()
            );
        }
        return $this->user;
    }

    public function sayTop10()
    {
        try {
            $clientFactory = new AuthenticatedClientFactory($this->user());
            $client = $clientFactory->create();
            $currentUserLogin = $this->getBot()->userStorage()->get('userLogin');
            $users = new Users($client);
            $currentUserId = $users->getUserIdByLogin($currentUserLogin);
            $admission = new Admission($client);
            $last10Admissions = array_slice($admission->getByUserId($currentUserId)['data']['admission'], 0, 10, true);
            if (!empty($last10Admissions)) {
                foreach ($last10Admissions as $concrete) {
                    $message = $concrete['admission_date'] .PHP_EOL;
                    if (isset($concrete['client'])) {
                        $message .= "Клиент: ";
                        $message .= $concrete['client']['last_name'] . " " . $concrete['client']['first_name'] . PHP_EOL;
                    } else {
                        $message .= "Клиент: <пусто>";
                    }
                    if (isset($concrete['pet'])) {
                        $message .= "Кличка питомца: " . $concrete['pet']['alias'] . PHP_EOL;
                        $message .= "Тип: " . $concrete['pet']['pet_type_data']['title'] . PHP_EOL;
                        $message .= "Порода: " . $concrete['pet']['breed_data']['title'];
                    }
                    $this->say($message);
                }
            } else {
                $this->say("У вас нет запланированных визитов.");
            }
        } catch (\Throwable $exception) {
            $this->sayError("Ошибка: " . $exception->getMessage());
        }
    }

    public function sayError(string $message) {
        $this->say($message);
        $this->say(
            (
                new MainMenu(
                    [Question::class, 'create'],
                    [Button::class, 'create'],
                    $this->user()->isAuthorized()
                )
            )->asQuestion()
        );
    }

    public function run()
    {
        $this->sayTop10();
    }
}
