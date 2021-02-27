<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Helpers\Rest\Pets;
use App\Vetmanager\Api\AuthenticatedClientFactory;
use App\Vetmanager\Notification\Messages\Admission\AdmissionMessageData;
use App\Vetmanager\Notification\Messages\RollbackMessage;
use App\Vetmanager\Notification\Notification;
use App\Vetmanager\Notification\Routers\ConcretteUserRoute;
use App\Vetmanager\Notification\Routers\EveryoneRoute;
use App\Vetmanager\UserData\UserRepository\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


final class NotificationsController extends Controller
{
    public function handleNotifications(Request $request, $domain)
    {
        $botman = resolve('botman');
        $input = $request->all();
        $users = DB::table('users')
            ->where('clinic_domain', '=', $domain)
            ->where('notification_enabled', '=', true);
        switch ($input['name']) {
            case 'invoiceRollback':
                $notification = new Notification(new RollbackMessage($input), new EveryoneRoute($users->get()->toArray()), $botman);
                $notification->send();
            break;
            case 'admissionAdd':
            case 'admissionEdit':
            case 'admissionConfirm':
                $messageClass = 'App\Vetmanager\Notification\Messages\Admission\\'.ucfirst($input['name']).'Message';
                $userId = $input['data']['user_id'];
                $user = $users->where('vm_user_id', '=', $userId)->get()->toArray();
                if (!empty($user)) {
                    $clientFactory = new AuthenticatedClientFactory(UserRepository::getById($user[0]->chat_id));
                    $client = $clientFactory->create();
                    $pets = new Pets($client);
                    $pet = $pets->byId(intval($input['data']['patient_id']))['data']['pet'];
                    $admissionData = new AdmissionMessageData($pet, $input);
                    $notification = new Notification(new $messageClass($admissionData), new ConcretteUserRoute($user, $input), $botman);
                    $notification->send();
                }
            break;
        }
    }
}
