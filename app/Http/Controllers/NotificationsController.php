<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Vetmanager\Logging\NotificationLogger;
use App\Vetmanager\Notification\Messages\Admission\AdmissionAddMessage;
use App\Vetmanager\Notification\Messages\Admission\AdmissionConfirmMessage;
use App\Vetmanager\Notification\Messages\Admission\AdmissionDirectedMessage;
use App\Vetmanager\Notification\Messages\Admission\AdmissionEditMessage;
use App\Vetmanager\Notification\Messages\Admission\AdmissionInTreatmentMessage;
use App\Vetmanager\Notification\Messages\Admission\AdmissionMessageDataFactory;
use App\Vetmanager\Notification\Messages\RollbackMessage;
use App\Vetmanager\Notification\Notification;
use App\Vetmanager\Notification\Routers\ConcretteUserRoute;
use App\Vetmanager\Notification\Routers\EveryoneRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


final class NotificationsController extends Controller
{
    public function handleNotifications(Request $request, $domain)
    {
        $botman = resolve('botman');
        $input = $request->all();
        switch ($input['name']) {
            case 'invoiceRollback':
                $users = $this->allDomainUsers($domain);
                $notification = new Notification(new RollbackMessage($input), new EveryoneRoute($users), $botman);
                $notification->setLogger((new NotificationLogger()));
                $notification->send();
            break;
            case 'admissionAdd':
                $userId = $input['data']['user_id'];
                $user = $this->currentUser($domain, $userId);
                $notification = new Notification(
                    new AdmissionAddMessage(
                        (new AdmissionMessageDataFactory($user, $input))->create()
                    ),
                    new ConcretteUserRoute($user),
                    $botman
                );
                $notification->setLogger((new NotificationLogger()));
                $notification->send();
                break;
            case 'admissionEdit':
                $userId = $input['data']['user_id'];
                $user = $this->currentUser($domain, $userId);
                $notification = new Notification(
                    new AdmissionEditMessage(
                        (new AdmissionMessageDataFactory($user, $input))->create()
                    ),
                    new ConcretteUserRoute($user),
                    $botman
                );
                $notification->setLogger((new NotificationLogger()));
                $notification->send();
                break;
            case 'admissionConfirm':
                $userId = $input['data']['user_id'];
                $user = $this->currentUser($domain, $userId);
                $notification = new Notification(
                    new AdmissionConfirmMessage(
                        (new AdmissionMessageDataFactory($user, $input))->create()
                    ),
                    new ConcretteUserRoute($user),
                    $botman
                );
                $notification->setLogger((new NotificationLogger()));
                $notification->send();
            break;
            case 'admissionDirected':
                $userId = $input['data']['user_id'];
                $user = $this->currentUser($domain, $userId);
                $notification = new Notification(
                    new AdmissionDirectedMessage(
                        (new AdmissionMessageDataFactory($user, $input))->create()
                    ),
                    new ConcretteUserRoute($user),
                    $botman
                );
                $notification->setLogger((new NotificationLogger()));
                $notification->send();
                break;
            case 'admissionInTreatment':
                $userId = $input['data']['user_id'];
                $user = $this->currentUser($domain, $userId);
                $notification = new Notification(
                    new AdmissionInTreatmentMessage(
                        (new AdmissionMessageDataFactory($user, $input))->create()
                    ),
                    new ConcretteUserRoute($user),
                    $botman
                );
                $notification->setLogger((new NotificationLogger()));
                $notification->send();
                break;
        }
    }

    private function allDomainUsers($domain) {
        return DB::table('users')
        ->where('clinic_domain', '=', $domain)
        ->where('notification_enabled', '=', true)->get()->toArray();
    }

    private function currentUser($domain, $userId) {
        $user = DB::table('users')
            ->where('clinic_domain', '=', $domain)
            ->where('vm_user_id', '=', $userId)
            ->where('notification_enabled', '=', true)->get()->toArray();
        if (empty($user)) {
            throw new \Exception("This user is not in the database");
        }
        return $user;
    }
}
