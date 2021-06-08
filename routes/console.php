<?php

use App\Http\Helpers\Rest\UsersApi;
use Otis22\VetmanagerUrl\Url\Part\Domain;
use App\Http\Helpers\Rest\ComboManualApi;
use App\Vetmanager\Notification\SendAction;
use Illuminate\Support\Facades\DB;
use App\Http\Helpers\Rest\AdmissionApi;
use App\Http\Helpers\Rest\SchedulesApi;
use App\Vetmanager\Logging\ScheduleLogger;
use App\Vetmanager\Api\AuthenticatedClientFactory;
use App\Vetmanager\MessageBuilder\Admission\AdmissionMessageBuilder;
use App\Vetmanager\MessageBuilder\Timesheet\TimesheetMessageBuilder;
use App\Vetmanager\MessageBuilder\Statistics\StatisticsMessageBuilder;
use App\Vetmanager\MessageBuilder\Statistics\StatisticsMessageData;
use App\Vetmanager\Notification\StatisticsSendAction;
use Illuminate\Foundation\Inspiring;
use App\Vetmanager\UserData\UserRepository\UserRepository;
use App\Vetmanager\Notification\Notification;
use App\Vetmanager\Notification\Messages\Message;
use App\Vetmanager\Notification\Routers\ConcretteUserRoute;
use Illuminate\Support\Facades\URL;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('url', function () {
    echo URL::to('/');
})->describe('Display application URL');

Artisan::command('fix_notification_route', function () {
    $users = UserRepository::all();
    foreach ($users as $user) {
        $clientFactory = new AuthenticatedClientFactory($user);
        $comboManual = new ComboManualApi($clientFactory->create());
        try {
            $comboManual->updateExistNotificationRoute($user->getDomain());
        } catch (Exception $e) {}
    }
})->describe('Change Vetmanager\'s hook url to actual');

Artisan::command('fix_domains', function () {
    $users = UserRepository::all();
    foreach ($users as $user) {
        DB::table('users')
            ->where('chat_id', '=', $user->getId())
            ->update(['clinic_domain' => (new Domain($user->getDomain()))->asString()]);
    }
})->describe('Fix domain names');

Artisan::command('send_schedule', function () {
    $users = UserRepository::all();
    foreach ($users as $user) {
        $botman = resolve('botman');
        $logger = new ScheduleLogger();
        $dbUser = DB::table('users')->where('chat_id', '=', $user->getId())->get()->toArray();
        $client = (new AuthenticatedClientFactory($user))->create();
        $currentUserId = $user->getVmUserId();
        try {
            $schedules = new SchedulesApi($client);
            $timesheets = $schedules->byIntervalInDays(1, $currentUserId);
            $messageBuilder = new TimesheetMessageBuilder($timesheets, $schedules);
            $scheduleMessage = $messageBuilder->buildMessage();
            $notification = new Notification(
                new Message($scheduleMessage),
                new ConcretteUserRoute($dbUser),
                new SendAction($botman, $logger)
            );
            $notification->send();
        } catch (Throwable $e) {
            echo $e->getMessage();
        }
        try {
            $admissions = (new AdmissionApi($client))->getByUserId($currentUserId);
            $tomorrowAdmissions = array_filter($admissions, function ($admission) {
                $today = new DateTime();
                $today->setTime(0,0);
                $admissionDate = new DateTime($admission['admission_date']);
                return ($today->diff($admissionDate)->days === 1);
            });
            $messageBuilder = new AdmissionMessageBuilder($tomorrowAdmissions);
            $admissionsMessage = $messageBuilder->buildMessage();
            $notification = new Notification(
                new Message($admissionsMessage),
                new ConcretteUserRoute($dbUser),
                new SendAction($botman, $logger)
            );
            $notification->send();
        } catch (Throwable $e) {
            echo $e->getMessage();
        }
    }
})->describe('Run sheduled messages with user appointments and time table');

Artisan::command('send_stats', function () {
    $users = UserRepository::all();
    $botman = resolve('botman');
    $logger = new ScheduleLogger();
    foreach ($users as $user) {
        $dbUser = DB::table('users')->where('chat_id', '=', $user->getId())->get()->toArray();
        $statsMessageBuilder = new StatisticsMessageBuilder(
            (new StatisticsMessageData($user))->asArray()
        );
        $statsMessage = $statsMessageBuilder->buildMessage();
        $notification = new Notification(
            new Message($statsMessage),
            new ConcretteUserRoute($dbUser),
            new StatisticsSendAction($botman, $logger)
        );
        $notification->send();
    }
    })->describe('Send stats to users');

