<?php
use App\Http\Controllers\BotManController;
use BotMan\BotMan\BotMan;
use App\Http\Controllers\VetmanagerController;

$botman = resolve('botman');

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

$botman->hears('Start conversation', BotManController::class.'@startConversation');

$botman->hears('start', VetmanagerController::class.'@authConversation');