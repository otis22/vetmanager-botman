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

        Cache::put('today_cache', $todayVisits, 10);

        if (Cache::has('today_cache')) {
            return view ('visits.today')->with(['todayVisits' => Cache::get('today_cache')]);
        }
    }

    public function weekCount($md5)
    {
        $week = new ServiceModel();
        $weekVisits = $week->getImg('week', $week->getWeekCount($md5));

        Cache::put('week_cache', $weekVisits, 10);

        if (Cache::has('week_cache')) {
            return view('visits.week')->with(['weekVisits' => Cache::get('week_cache')]);
        }
    }
}