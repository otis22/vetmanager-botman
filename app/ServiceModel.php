<?php

namespace App;

use Abyzs\VetmanagerVisits\Invoices;
use Abyzs\VetmanagerVisits\InvoiceFilter;
use Abyzs\VetmanagerVisits\TodayVisits;
use Abyzs\VetmanagerVisits\WeekVisits;
use App\Vetmanager\UserData\UserRepository\UserRepository;
use DateInterval;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\In;
use function Otis22\VetmanagerUrl\url;
use function Otis22\VetmanagerRestApi\byToken;


class ServiceModel
{
    private function auth($md5): array
    {
        $userId = $this->userIdByHash($md5);
        $user = UserRepository::getById($userId);
        $appName = config('app.name');

        $invoices = new Invoices(
            new Client(['base_uri' => url($user->getDomain())->asString()]),
            byToken($appName, $user->getToken()),
            new InvoiceFilter(new DateInterval('P1D'))
        );

        return $invoices->give();
    }

    private function todayVisits($md5): int
    {
        $today = new TodayVisits();
        $todayVisits = $today->count($this->auth($md5));

        if($todayVisits >= 1000) {
            $todayVisits/1000 . "k";
        } else {
            $todayVisits;
        }
        return $todayVisits;
    }

    public function todayCache($md5)
    {
        $key = 'today' . $md5;
        $todayCache = Cache::get($key, false);
        if(!$todayCache){
            Cache::put($key, $this->todayVisits($md5), 600);
            $todayCache = Cache::get($key, false);
        }
        return $todayCache;
    }

    private function weekVisits($md5): int
    {
        $week = new WeekVisits();
        $weekVisits = $week->count($this->auth($md5));

        if($weekVisits >= 1000) {
            $weekVisits/1000 . "k";
        } else {
            $weekVisits;
        }
        return $weekVisits;
    }

    public function weekCache($md5)
    {
        $key = 'week' . $md5;
        $weekCache = Cache::get($key, false);
        if(!$weekCache){
            Cache::put($key, $this->weekVisits($md5), 600);
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
