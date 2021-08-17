<?php

namespace App\Http\Controllers;

use App\ServiceModel;


class ServiceController extends Controller
{
    public function visits($md5)
    {
        $service = new ServiceModel();
        $todayVisits = $service->todayCache($md5);
        $weekVisits = $service->weekCache($md5);

        return view('visits.all')->with(
            [
                'md5' => $md5,
                'todayVisits' => $todayVisits,
                'weekVisits' => $weekVisits
            ]
        );
    }

    public function todayCount($md5)
    {
        $today = new ServiceModel();
        $todayVisits = $today->todayCache($md5);

        return response()
            ->view('visits.today', compact("todayVisits"))
            ->header('Content-type', 'image/svg+xml');
    }

    public function weekCount($md5)
    {
        $week = new ServiceModel();
        $weekVisits = $week->weekCache($md5);

        return response()
            ->view('visits.week', compact("weekVisits"))
            ->header('Content-type', 'image/svg+xml');
    }
}
