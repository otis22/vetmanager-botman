<?php

namespace App;

use Abyzs\VetmanagerVisits\Invoices;
use Abyzs\VetmanagerVisits\InvoiceFilter;
use Abyzs\VetmanagerVisits\TodayVisits;
use Abyzs\VetmanagerVisits\WeekVisits;
use App\Vetmanager\UserData\UserRepository\UserInterface;
use App\Vetmanager\UserData\UserRepository\UserRepository;
use DateInterval;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use function Otis22\VetmanagerUrl\url;
use function Otis22\VetmanagerRestApi\byToken;


class ServiceModel
{
    private function todayInvoices($md5): array
    {
        $todayInvoices = new Invoices(
            new Client(['base_uri' => url($this->auth($md5)->getDomain())->asString()]),
            byToken(config('app.name'), $this->auth($md5)->getToken()),
            new InvoiceFilter(new DateInterval('P0D'))
        );
        return $todayInvoices->give();
    }

    private function todayVisits($md5): int
    {
        $today = new TodayVisits();
        $todayVisits = $today->count($this->todayInvoices($md5));

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

    private function weekInvoices($md5): array
    {
        $appName = config('app.name');

        $weekInvoices = new Invoices(
            new Client(['base_uri' => url($this->auth($md5)->getDomain())->asString()]),
            byToken($appName, $this->auth($md5)->getToken()),
            new InvoiceFilter(new DateInterval('P7D'))
        );
        return $weekInvoices->give();
    }

    private function weekVisits($md5): int
    {
        $week = new WeekVisits();
        $weekVisits = $week->count($this->weekInvoices($md5));

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

    private function auth ($md5): UserInterface
    {
        return UserRepository::getById($this->userIdByHash($md5));
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
