<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Vetmanager\Notification\Messages\RollbackMessage;
use App\Vetmanager\Notification\Notification;
use App\Vetmanager\Notification\Routers\EveryoneRoute;
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
                $notification = new Notification(new RollbackMessage($input), new EveryoneRoute($users, $input), $botman);
                $notification->send();
            break;
        }
    }
}
