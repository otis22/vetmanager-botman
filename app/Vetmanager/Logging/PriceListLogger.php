<?php


namespace App\Vetmanager\Logging;

use Illuminate\Support\Facades\DB;

class PriceListLogger implements LoggerInterface
{
    public function log($user)
    {
        DB::table('statistic')->insert([
            'created_at' => date("Y-m-d H:i:s"),
            'user_id' => $user->getId(),
            'channel' => 'browser',
            'event' => 'open price list'
        ]);
    }
}
