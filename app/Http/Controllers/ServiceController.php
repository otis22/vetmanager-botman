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
        $content = view('visits.today')->with(['todayVisits' => $todayCache]);
        return response($content)->header('Content-type', 'image/svg+xml');
    }

    public function weekCount($md5)
    {
        $week = new ServiceModel();
        $weekVisits = $week->getImg('week', $week->getWeekCount($md5));

        $key = 'week' . $md5;
        $weekCache = Cache::get($key);

        if($weekCache === null) {
            Cache::put($key, $weekVisits, 600);
            $weekCache = Cache::get($key);
        }
        $content = view('visits.week')->with(['weekVisits' => $weekCache]);
        return response($content)->header('Content-type', 'image/svg+xml');
    }
}