<?php

namespace App\Http\Controllers;

use App\Vetmanager\UserData\UserRepository\UserRepository;
use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BotManController extends Controller
{

    public function handle(Request $request)
    {
        $botman = app('botman');
        $input = $request->all();
        if ($this->isUserUpdated($input)) {
            if ($this->isKicked($input))
            {
                $user = UserRepository::getById($input['my_chat_member']['from']['id']);
                $user->block();
                UserRepository::save($user);
            }
            if ($this->isMember($input)) {
                $user = UserRepository::getById($input['my_chat_member']['from']['id']);
                $user->unblock();
                UserRepository::save($user);
            }
        }
        $botman->listen();
    }

    private function isUserUpdated($input)
    {
        return isset($input['my_chat_member']);
    }

    private function isKicked($input)
    {
        return $input['my_chat_member']['new_chat_member']['status'] == 'kicked';
    }

    private function isMember($input)
    {
        return $input['my_chat_member']['new_chat_member']['status'] == 'member';
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
        $activeUsers = DB::table('users')->where('is_blocked', '=', '0')->get();
        $blockedUsers = DB::table('users')->where('is_blocked', '=', '1')->get();
        $notifies = DB::table('statistic')->where('event', '=', 'notification message')->count();
        $reviews = DB::table('review')->get()->toArray();
        $marks = array_column($reviews, 'mark');
        $avgReviewMark = (!empty($marks)) ? array_sum($marks) / count($marks) : "Оценок нет";
        $statistic = DB::table('statistic')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->latest('date')
            ->take(10)->get()->toArray();
        $statistic = array_reverse($statistic);
        $eventsLast10Days['labels'] = array_column($statistic, 'date');
        $eventsLast10Days['data'] = array_column($statistic, 'count');
        return view('stats')->with(
            [
                'notifies' => $notifies,
                'activeUsers' => $activeUsers,
                'blockedUsers' => $blockedUsers,
                'eventsLast10Days' => $eventsLast10Days,
                'reviews' => $reviews,
                'avgReviewMark' => $avgReviewMark
            ]
        );
    }
}
