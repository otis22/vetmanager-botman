<?php

namespace App\Http\Controllers;

use Abyzs\VetmanagerVisits\AuthToken;
use Abyzs\VetmanagerVisits\VisitCounter;
use App\Vetmanager\UserData\UserRepository\UserRepository;
use Illuminate\Support\Facades\DB;


class ServiceController extends Controller
{
    public function dayCount($md5)
    {
        $userId = $this->userIdByHash($md5);
        $user = UserRepository::getById($userId);
        $appName = config('app.name');
        $auth = new AuthToken($user->getDomain(), $appName, $user->getToken());
        $day = new VisitCounter();
        $dayVisits = $day->dayCount($auth->getInvoices());

        if($dayVisits >= 1000) {
            $dayVisits/1000 . "k";
        } else {
            $dayVisits;
        }
        return view ('visits.day')->with(['dayVisits' => $dayVisits]);
    }

    public function weekCount($md5)
    {
        $userId = $this->userIdByHash($md5);
        $user = UserRepository::getById($userId);
        $appName = config('app.name');
        $auth = new AuthToken($user->getDomain(), $appName, $user->getToken());
        $week = new VisitCounter();
        $weekVisits = $week->weekCount($auth->getInvoices());

        if($weekVisits >= 1000) {
            $weekVisits/1000 . "k";
        } else {
            $weekVisits;
        }
        return view ('visits.week')->with(['weekVisits' => $weekVisits]);
    }

    private function userIdByHash($md5)
    {
        $userId = DB::table('users')->where(DB::raw('md5(CONCAT(clinic_domain, vm_user_id))'), $md5)->value('chat_id');
        if (!$userId) {
            throw new \Exception("Bad link");
        }
        return $userId;
    }
}