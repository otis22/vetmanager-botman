<?php
/**
 * https://dev.to/devkiran/building-a-salon-booking-chatbot-with-laravel-and-botman-1250
 */
declare(strict_types=1);

namespace App\Conversations;

use App\Http\Helpers\Rest\Users;
use App\Vetmanager\UserData\UserRepository\User;
use App\Vetmanager\UserData\UserRepository\UserRepository;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Storages\Storage;
use GuzzleHttp\Client;

use function Otis22\VetmanagerUrl\url;
use function Otis22\VetmanagerToken\token;
use function Otis22\VetmanagerToken\credentials;
use function config;

final class AuthConversation extends VetmanagerConversation
{
    /**
     * @var string
     */
    protected $clinicUrl;
    /**
     * @var string
     */
    protected $userLogin;
    /**
     * @var string
     */
    protected $token;

    /**
     * @return Conversation
     */
    public function askDomain(): Conversation
    {
        return $this->ask("Введите доменное имя или адрес программы. Пример: myclinic или https://myclinic.vetmanager.ru", function (Answer $answer) {
            try {
                if (empty(trim($answer->getText()))) {
                    throw new \Exception("Can't be empty text");
                }
                $domainName = $answer->getText();
                $this->getBot()->userStorage()
                    ->save(
                        ['clinicDomain' => $domainName]
                    );
                $this->clinicUrl = url($domainName)->asString();
                $this->askLogin();
            } catch (\Throwable $exception) {
                $this->say("Попробуйте еще раз. Ошибка: " . $exception->getMessage());
                $this->askDomain();
            }
        });
    }

    /**
     * @return Conversation
     */
    public function askLogin(): Conversation
    {
        return $this->ask("Введите login вашего пользователя в Ветменеджер", function (Answer $answer) {
            $this->userLogin = $answer->getText();
            $this->getBot()->userStorage()
                ->save(
                    ['userLogin' => $this->userLogin]
                );
            $this->askPassword();
        });
    }

    public function askPassword(): Conversation
    {
        return $this->ask("Введите пароль вашего пользователя в Ветменеджер", function (Answer $answer) {
            $password = $answer->getText();
            $credentials = credentials(
                $this->userLogin,
                $password,
                config('app.name')
            );
            try {
                $token = token($credentials, $this->clinicUrl)->asString();
                $chatId = $this->getBot()->getUser()->getId();
                $client = new Client(
                    [
                        'base_uri' => $this->clinicUrl,
                        'headers' => ['X-USER-TOKEN' => $token, 'X-APP-NAME' => config('app.name')]
                    ]
                );
                $vmUserId = (new Users($client))->getUserIdByToken($token);
                $user = new User(
                    $chatId,
                    $this->getBot()->userStorage()->get('clinicDomain'),
                    $token,
                    $vmUserId,
                    $this->getBot()->getDriver()->getName()
                );
                UserRepository::save($user);
                $this->getBot()->userStorage()->save(['is_authorized' => true]);
                $this->say('Успех!');
                $this->endConversation();
            } catch (\Throwable $exception) {
                $this->say("Попробуйте еще раз. Ошибка: " . $exception->getMessage());
                $this->askDomain();
            }
        });
    }

    public function run()
    {
        if (empty($this->userData()->get('clinicUrl'))) {
            $this->say("Привет, Босс, ответьте на 3 вопроса");
            $this->askDomain();
            return;
        }
        if (empty($this->userData()->get('clinicUserLogin'))) {
            $this->say("Привет, Босс, ответьте на 2 вопроса");
            $this->askLogin();
            return;
        }
    }
    private function userData(): Storage
    {
        return $this->getBot()
            ->userStorage();
    }

}
