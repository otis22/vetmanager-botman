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

        $key = 'today_cache';
        $todayCache = Cache::get($key);

        if($todayCache === null) {
            Cache::put($key, $todayVisits, 6);
            $todayCache = Cache::get($key);
        }
        return view ('visits.today')->with(['todayVisits' => $todayCache]);
    }

    public function weekCount($md5)
    {
        $week = new ServiceModel();
        $weekVisits = $week->getImg('week', $week->getWeekCount($md5));

        $key = 'week_cache';
        $weekCache = Cache::get($key);

        if($weekCache === null) {
            Cache::put($key, $weekVisits, 6);
            $weekCache = Cache::get($key);
        }
        return view('visits.week')->with(['weekVisits' => $weekCache]);
    }
}