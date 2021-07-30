<?php

namespace App\Http\Controllers;

use App\ServiceModel;
use Illuminate\Support\Facades\Cache;


class ServiceController extends Controller
{
    public function todayCount($md5)
    {
        $today = new ServiceModel();
        $todayVisits = $today->getImg('today', $today->getTodayVisits($md5));

        $key = 'today' . $md5;
        $todayCache = Cache::get($key);

        if($todayCache === null) {
            Cache::put($key, $todayVisits, 600);
            $todayCache = Cache::get($key);
        }
        return response()
            ->view('visits.today', ['todayVisits' => $todayCache])
            ->header('Content-type', 'image/svg+xml');
    }


    public function weekCount($md5)
    {
        $weekCache = new ServiceModel();

        return response()
            ->view('visits.week', ['weekVisits' => $weekCache->getWeekCache($md5)])
            ->header('Content-type', 'image/svg+xml');
    }
}