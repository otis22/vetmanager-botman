<?php

namespace App\Vetmanager\UserData\UserRepository;

use Illuminate\Support\Facades\DB;

class UserRepository implements IUserRepository
{

    protected $chatId;
    protected $domain;
    protected $token;
    protected $notificationEnabled;

    public function __construct($chatId, $domain, $token, $notificationEnabled=false)
    {
        $this->chatId = $chatId;
        $this->domain = $domain;
        $this->token = $token;
        $this->notificationEnabled = $notificationEnabled;
    }

    public static function create($chatId, $domain, $token)
    {
        DB::table('users')->insert([
            'chat_id' => $chatId,
            'clinic_domain' => $domain,
            'clinic_token' => $token
        ]);
        return new self($chatId, $domain, $token);
    }

    public static function getById($chatId)
    {
        $user = DB::table('users')->where('chat_id', '=', $chatId)->get()->toArray();
        return new self($user['chat_id'], $user['clinic_domain'], $user['clinic_token'], $user['notification_enabled']);
    }

    public function getId()
    {
        return $this->chatId;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function isNotificationEnabled()
    {
        return $this->notificationEnabled;
    }

    public function setNotifications($bool)
    {
        DB::table('users')->where('chat_id', '=', $this->getId())->update(['notification_enable' => $bool]);
        $this->notificationEnabled = $bool;
    }
}