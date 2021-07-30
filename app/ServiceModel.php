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

    public function getTodayVisits($md5): int
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

    public function getWeekCount($md5)
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

    public function getImg($string, $count)
    {
        header('Content-type: image/svg+xml');

        echo  '<svg
    xmlns="http://www.w3.org/2000/svg"
    xmlns:xlink="http://www.w3.org/1999/xlink" width="108" height="20" role="img" aria-label="jjj: $num">
    <title>: ' . $string . '</title>
    <linearGradient id="s" x2="0" y2="100%">
        <stop offset="0" stop-color="#bbb" stop-opacity=".1"/>
        <stop offset="1" stop-opacity=".1"/>
    </linearGradient>
    <clipPath id="r">
        <rect width="108" height="20" rx="3" fill="#fff"/>
    </clipPath>
    <g clip-path="url(#r)">
        <rect width="77" height="20" fill="#555"/>
        <rect x="77" width="31" height="20" fill="#97ca00"/>
        <rect width="108" height="20" fill="url(#s)"/>
    </g>
    <g fill="#fff" text-anchor="middle" font-family="Verdana,Geneva,DejaVu Sans,sans-serif" text-rendering="geometricPrecision" font-size="110">
        <text aria-hidden="true" x="395" y="150" fill="#010101" fill-opacity=".3" transform="scale(.1)" textLength="670">' . $string . '</text>
        <text x="395" y="140" transform="scale(.1)" fill="#fff" textLength="670">' . $string . '</text>
        <text aria-hidden="true" x="915" y="150" fill="#010101" fill-opacity=".3" transform="scale(.1)" textLength="210">' . $count . '</text>
        <text x="915" y="140" transform="scale(.1)" fill="#fff" textLength="210">' . $count . '</text>
    </g>
</svg>';
    }

    public function getWeekCache($md5)
    {
        $week = $this->getImg('week', $this->getWeekCount($md5));
        $key = 'week' . $md5;

        $weekCache = Cache::get($key, false);

        if($weekCache === null){
            $weekCache = Cache::put($key, $week, 6);
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
