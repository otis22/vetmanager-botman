<?php


namespace App\Http\Controllers;


use App\Http\Helpers\Rest\GoodsApi;
use App\Vetmanager\Api\AuthenticatedClientFactory;
use App\Vetmanager\UserData\UserRepository\UserRepository;
use Illuminate\Support\Facades\DB;

class PriceController
{
    public function groups($md5)
    {
        $userId = $this->userIdByHash($md5);
        $user = UserRepository::getById($userId);
        $client = (new AuthenticatedClientFactory($user))->create();
        $goodsApi = new GoodsApi($client);
        $goodGroups = $goodsApi->goodGroups();

        return view('price.groups')->with(
            [
                'goodGroups' => $goodGroups
            ]
        );
    }

    public function priceList($md5, $clinicId, $groupId)
    {
        $userId = $this->userIdByHash($md5);
        $user = UserRepository::getById($userId);
        $client = (new AuthenticatedClientFactory($user))->create();
        $goodsApi = new GoodsApi($client);
        $goods = $goodsApi->goodsByGroup($groupId);

        $goodsToView = [];
        foreach ($goods as $good) {
            $saleParams = $good['goodSaleParams'];
            foreach ($saleParams as $saleParam) {
                if ($saleParam['clinic_id'] == $clinicId) {
                    $calculatedPrice = $goodsApi->getGoodCalculatedPrice($good['id'], $clinicId, $saleParam['id']);
                    if ($calculatedPrice) {
                        $goodsToView[] = ['title' => $good['title'], 'price' => $calculatedPrice];
                    } else {
                        $goodsToView[] = ['title' => $good['title'], 'price' => $saleParam['price']];
                    }
                }
            }
        }
        return view('price.list')->with(
            [
                'goods' => $goodsToView
            ]
        );
    }

    private function userIdByHash($md5)
    {
        $userId = DB::table('users')->where(DB::raw('md5(CONCAT(clinic_domain, vm_user_id))'), $md5)->value('chat_id');
        if (!$userId) {
            throw new \Exception("Bad link");
        }
        return $userId;
    }

}