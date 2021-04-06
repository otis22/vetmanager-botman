<?php


namespace App\Vetmanager\Logging;

use Illuminate\Support\Facades\DB;

class ScheduleLogger implements LoggerInterface
{
    public function log($user)
    {
        DB::table('statistic')->insert([
            'created_at' => date("Y-m-d H:i:s"),
            'user_id' => $user->chat_id,
            'channel' => $user->channel,
            'event' => 'scheduled message'
        ]);
    }
}
