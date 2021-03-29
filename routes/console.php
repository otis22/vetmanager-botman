<?php

use Illuminate\Support\Facades\DB;
use App\Http\Helpers\Rest\AdmissionApi;
use App\Http\Helpers\Rest\SchedulesApi;
use App\Http\Helpers\Rest\UsersApi;
use App\Vetmanager\Api\AuthenticatedClientFactory;
use App\Vetmanager\MessageBuilder\Admission\AdmissionMessageBuilder;
use App\Vetmanager\MessageBuilder\Admission\TimesheetMessageBuilder;
use Illuminate\Foundation\Inspiring;
use App\Vetmanager\UserData\UserRepository\UserRepository;
use App\Vetmanager\Notification\Notification;
use App\Vetmanager\Notification\Messages\ScheduledMessage;
use App\Vetmanager\Notification\Routers\ConcretteUserRoute;

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

Artisan::command('send_schedule', function () {
    $users = UserRepository::all();
    foreach ($users as $user) {
        $client = (new AuthenticatedClientFactory($user))->create();
        $currentUserId = $user->getVmUserId();

        $schedules = new SchedulesApi($client);
        $timesheets = $schedules->byIntervalInDays(1, $currentUserId)['data']['timesheet'];
        if (!empty($timesheets)) {
            $messageBuilder = new TimesheetMessageBuilder($timesheets, $schedules);
            $scheduleMessage = $messageBuilder->buildMessage();
        } else {
            $scheduleMessage = "Не заданно";
        }

        $admissions = (new AdmissionApi($client))->getByUserId($currentUserId)['data']['admission'];
        if (!empty($admissions)) {
            $tomorrowAdmissions = array_filter($admissions, function ($admission) {
                $today = new DateTime();
                $today->setTime(0,0);
                $admissionDate = new DateTime($admission['admission_date']);
                return ($today->diff($admissionDate)->days === 1);
            });
            $messageBuilder = new AdmissionMessageBuilder($tomorrowAdmissions);
            $admissionsMessage = $messageBuilder->buildMessage();
        } else {
            $admissionsMessage = "Планов нет.";
        }

        $botman = resolve('botman');
        $dbUser = DB::table('users')->where('chat_id', '=', $user->getId())->get()->toArray();
        $notification = new Notification(
            new ScheduledMessage($scheduleMessage, $admissionsMessage),
            new ConcretteUserRoute($dbUser),
            $botman
        );
        $notification->send();
    }
})->describe('Run sheduled messages with user appointments and time table');
