<?php


namespace App\Http\Controllers;


use App\Http\Helpers\Rest\GoodsApi;
use App\Vetmanager\Api\AuthenticatedClientFactory;
use App\Vetmanager\UserData\UserRepository\UserRepository;
use Illuminate\Support\Facades\DB;

class PriceController
{
    public function price($md5)
    {
        $userId = DB::table('users')->where(DB::raw('md5(CONCAT(clinic_domain, vm_user_id))'), $md5)->value('chat_id');
        if (!$userId) {
            throw new \Exception("Oh no");
        }
        $user = UserRepository::getById($userId);
        $client = (new AuthenticatedClientFactory($user))->create();
        $goodsApi = new GoodsApi($client);
        $goodGroups = $goodsApi->goodGroups();

        return view('price')->with(
            [
                'goodGroups' => $goodGroups
            ]
        );
    }
}