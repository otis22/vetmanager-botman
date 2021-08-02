<?php

namespace App\Http\Controllers;

use App\ServiceModel;


class ServiceController extends Controller
{
    public function todayCount($md5)
    {
        $today = new ServiceModel();
        $todayVisits = $today->getTodayImg($md5);

        return response()
            ->view('visits.today', compact('todayVisits'))
            ->header('Content-type', 'image/svg+xml');
    }

    public function weekCount($md5)
    {
        $week = new ServiceModel();
        $weekVisits = $week->getWeekImg($md5);

        return response()
            ->view('visits.week', compact('weekVisits'))
            ->header('Content-type', 'image/svg+xml');
    }
}
