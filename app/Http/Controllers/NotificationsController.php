<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Helpers\Rest\Pets;
use App\Http\Helpers\Rest\Users;
use App\Vetmanager\Api\AuthenticatedClientFactory;
use App\Vetmanager\Notification\Messages\AdmissionMessage;
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
            ->where('notification_enabled', '=', true)
            ->get()->toArray();
        switch ($input['name']) {
            case 'invoiceRollback':
                $notification = new Notification(new RollbackMessage($input), new EveryoneRoute($users), $botman);
                $notification->send();
            break;
            case 'admissionAdd':
            case 'admissionEdit':
            case 'admissionConfirmed':
                $userId = $input['data']['user_id'];
                $user = array_where($users, function ($value, $key) use ($userId) {
                    return ($value->vm_user_id == $userId);
                });
                if (!empty($user)) {
                    $clientFactory = new AuthenticatedClientFactory(UserRepository::getById($user[0]->chat_id));
                    $client = $clientFactory->create();
                    $notification = new Notification(new AdmissionMessage($input, $client), new ConcretteUserRoute($user, $input), $botman);
                    $notification->send();
                }
            break;
        }
    }
}
