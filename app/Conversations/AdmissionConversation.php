<?php

declare(strict_types=1);

namespace App\Conversations;

use App\Http\Helpers\Rest\AdmissionApi;
use App\Vetmanager\Api\AuthenticatedClientFactory;
use App\Vetmanager\MainMenu;
use App\Vetmanager\MessageBuilders\Admission\AdmissionMessageBuilder;
use App\Vetmanager\UserData\UserRepository\UserInterface;
use App\Vetmanager\UserData\UserRepository\UserRepository;
use BotMan\BotMan\Messages\Conversations\Conversation;
use App\Http\Helpers\Rest\UsersApi;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;


final class AdmissionConversation extends VetmanagerConversation
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

    public function showTenNearestAdmissions()
    {
        try {
            $client = (new AuthenticatedClientFactory($this->user()))->create();
            $users = new UsersApi($client);
            $currentUserLogin = $this->getBot()->userStorage()->get('userLogin');
            $currentUserId = $users->getUserIdByLogin($currentUserLogin);
            $admission = new AdmissionApi($client);
            $last10Admissions = array_slice(
                $admission->getByUserId($currentUserId)['data']['admission'],
                0,
                10,
                true
            );
            $messageBuilder = new AdmissionMessageBuilder($last10Admissions);
            $message = $messageBuilder->buildMessage();
            $this->say($message);
        } catch (\Throwable $exception) {
            $this->sayError("Ошибка: " . $exception->getMessage());
        }
        $this->endConversation();
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
        $this->showTenNearestAdmissions();
    }
}
