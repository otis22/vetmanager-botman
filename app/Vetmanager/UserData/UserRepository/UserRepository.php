<?php

namespace App\Vetmanager\UserData\UserRepository;

use Illuminate\Support\Facades\DB;

class UserRepository implements IUserRepository
{
    public static function save(User $user): bool
    {
        $existUser = DB::table('users')->where('chat_id', '=', $user->getId());
        if (!empty($existUser->get()->toArray())) {
            return $existUser->update($user->toArray());
        }
        return DB::table('users')->insert($user->toArray());
    }

    public static function getById($chatId): User
    {
        $user = DB::table('users')->where('chat_id', '=', $chatId)->first();
        return new User($user->chat_id, $user->clinic_domain, $user->clinic_token, $user->vm_user_id, $user->notification_enabled);
    }
}