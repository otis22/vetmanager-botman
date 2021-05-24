<?php


namespace App\Conversations;


use App\Vetmanager\MainMenu;
use App\Vetmanager\UserData\UserRepository\UserInterface;
use App\Vetmanager\UserData\UserRepository\UserRepository;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

abstract class VetmanagerConversation extends Conversation
{
    protected $user;

    protected function user(): UserInterface
    {
        if (empty($this->user)) {
            $this->user = UserRepository::getById(
                $this->getBot()->getUser()->getId()
            );
        }
        return $this->user;
    }

    protected function endConversation()
    {
        $user = UserRepository::getById($this->getBot()->getUser()->getId());
        $this->getBot()->reply(
            (
            new MainMenu(
                [Question::class, 'create'],
                [Button::class, 'create'],
                $user->isAuthorized()
            )
            )->asQuestion()
        );
    }
}