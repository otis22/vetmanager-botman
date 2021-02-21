<?php

namespace App\Vetmanager\UserData\UserRepository;

use Illuminate\Support\Facades\DB;
use App\Vetmanager\UserData\UserRepository\UserInterface;

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

    public static function getById($chatId): UserInterface
    {
        $user = DB::table('users')->where('chat_id', '=', $chatId)->first();

        if (empty($user)) {
            return new IsNotAuthenticatedUser();
        }

        return new User($user->chat_id, $user->clinic_domain, $user->clinic_token, $user->vm_user_id, $user->notification_enabled);
    }
}