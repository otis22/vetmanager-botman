<?php

namespace App\Vetmanager\UserData\UserRepository;


interface IUserRepository
{
    public static function create($chatId, $domain, $token);
    public static function getById($chatId);
    public function getId();
    public function getDomain();
    public function getToken();
}