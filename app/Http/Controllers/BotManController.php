<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use App\Conversations\ExampleConversation;
use Illuminate\Support\Facades\DB;

class BotManController extends Controller
{
    /**
     * Place your BotMan logic here.
     */
    public function handle()
    {
        $botman = app('botman');

        $botman->listen();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tinker()
    {
        return view('tinker');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function stats()
    {
        $userCount = DB::table('users')->count();
        $notifies = DB::table('statistic')->where('event', '=', 'notification message')->count();
        $statistic = DB::table('statistic')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->latest('date')
            ->take(10)->get()->toArray();
        $statistic = array_reverse($statistic);
        $eventsLast10Days['labels'] = array_column($statistic, 'date');
        $eventsLast10Days['data'] = array_column($statistic, 'count');
        return view('stats')->with(compact(['notifies', 'userCount', 'eventsLast10Days']));
    }

    /**
     * Loaded through routes/botman.php
     * @param  BotMan $bot
     */
    public function startConversation(BotMan $bot)
    {
        $bot->startConversation(new ExampleConversation());
    }
}
