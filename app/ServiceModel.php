<?php

namespace App;

use Abyzs\VetmanagerVisits\AuthToken;
use Abyzs\VetmanagerVisits\VisitCounter;
use App\Vetmanager\UserData\UserRepository\UserRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;


class ServiceModel
{
    private function auth($md5): array
    {
        $userId = $this->userIdByHash($md5);
        $user = UserRepository::getById($userId);
        $appName = config('app.name');
        $auth = new AuthToken($user->getDomain(), $appName, $user->getToken());

        return $auth->getInvoices();
    }

    private function getTodayVisits($md5): int
    {
        $today = new VisitCounter();
        $todayVisits = $today->dayCount($this->auth($md5));

        if($todayVisits >= 1000) {
            $todayVisits/1000 . "k";
        } else {
            $todayVisits;
        }
        return $todayVisits;
    }

    public function getTodayCache($md5)
    {
        $key = 'today' . $md5;
        $todayCache = Cache::get($key, false);
        if(!$todayCache){
            Cache::put($key, $this->getTodayVisits($md5), 600);
            $todayCache = Cache::get($key, false);
        }
        return $todayCache;
    }

    private function getWeekVisits($md5): int
    {
        $week = new VisitCounter();
        $weekVisits = $week->weekCount($this->auth($md5));

        if($weekVisits >= 1000) {
            $weekVisits/1000 . "k";
        } else {
            $weekVisits;
        }
        return $weekVisits;
    }

    public function getWeekCache($md5)
    {
        $key = 'week' . $md5;
        $weekCache = Cache::get($key, false);
        if(!$weekCache){
            Cache::put($key, $this->getWeekVisits($md5), 600);
            $weekCache = Cache::get($key, false);
        }
        return $weekCache;
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
