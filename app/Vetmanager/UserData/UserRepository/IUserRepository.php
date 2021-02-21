<?php

namespace App\Vetmanager\UserData\UserRepository;


interface IUserRepository
{
    public static function save(UserInterface $user): bool;
    public static function getById($chatId): UserInterface;
}