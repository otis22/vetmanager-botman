<?php
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\BotMan;
use App\Http\Controllers\VetmanagerController;
use App\Vetmanager\MainMenu;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use App\Http\Middleware\BotMan\HeardMiddleware;
use App\Vetmanager\UserData\UserRepository\UserRepository;

$botman = resolve('botman');

$botman->middleware->heard((new HeardMiddleware()));

$botman->fallback(function (Botman $bot) {
    $bot->ask(
        Question::create("Я вас не понимаю, может с начала?")
            ->addButton(Button::create("start")->value('start')),
        function (Answer $answer) use ($bot) {
            if ($answer->isInteractiveMessageReply()) {
                switch ($answer->getValue()) {
                    case 'start':
                        $user = UserRepository::getById($bot->getUser()->getId());
                        $bot->reply(
                            (
                                new MainMenu(
                                    [Question::class, 'create'],
                                    [Button::class, 'create'],
                                    $user->isAuthorized()
                                )
                            )->asQuestion()
                        );
                        break;
                }
            }
        }
    );
});

$botman->hears('start', function($bot){
    $user = UserRepository::getById($bot->getUser()->getId());
    $bot->reply(
        (
            new MainMenu(
                [Question::class, 'create'],
                [Button::class, 'create'],
                $user->isAuthorized()
            )
        )->asQuestion()
    );
});
$botman->hears('auth', VetmanagerController::class.'@authConversation');
$botman->hears('timesheet', VetmanagerController::class.'@timesheetConversation');
$botman->hears('admissions', VetmanagerController::class.'@admissionConversation');
$botman->hears('notification', VetmanagerController::class.'@notificationConversation');
$botman->hears('review', VetmanagerController::class.'@reviewConversation');
$botman->hears('stats', VetmanagerController::class.'@statsConversation');
$botman->hears('clientBrief {id}', VetmanagerController::class.'@clientBriefConversation');
$botman->hears('pricelist', VetmanagerController::class.'@priceListConversation');



/** Examples
$botman->fallback(function (Botman $bot) {
$bot->reply(
Question::create("Я вас не понимаю, может с начала?")
->addButton(Button::create("start")->value('start'))
);
});


 *
 *
$botman->hears('Hi', function ($bot) {
$bot->reply('Hello!');
});

$botman->hears('Hello', function ($bot) {
$userStorage = $bot->userStorage($bot->getUser());
$bot->reply($userStorage->get('token'));
});

$botman->hears('call me {name}', function ($bot, $name) {
$bot->typesAndWaits(2);
$bot->userStorage()->save([
'name' => $name
]);

$bot->reply('I will call you '.$name);
});


$botman->hears("who am I", function (BotMan $bot) {
// Retrieve information for the currently logged in user.
// You can also pass a user-id / key as a second parameter.
$user = $bot->userStorage($bot->getUser());
if ($name = $user->get('name')) {
$bot->reply('You are ' . $name);
} else {
$bot->reply('I do not know you yet.');
}
});

 *
 */