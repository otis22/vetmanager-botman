<?php

namespace App\Vetmanager\UserData\UserRepository;

use Illuminate\Support\Facades\DB;

class UserRepository implements IUserRepository
{
    public static function save(UserInterface $user): bool
    {
        $now = date('Y-m-d H:i:s');
        $existUser = DB::table('users')->where('chat_id', '=', $user->getId());
        if (!empty($existUser->get()->toArray())) {
            return $existUser->update(array_merge($user->toArray(), ['updated_at' => $now]));
        }
        return DB::table('users')->insert(array_merge($user->toArray(), ['created_at' => $now]));
    }

    public static function getById($chatId): UserInterface
    {
        $user = DB::table('users')->where('chat_id', '=', $chatId)->first();

        if (empty($user)) {
            return new IsNotAuthenticatedUser();
        }

        return new User($user->chat_id, $user->clinic_domain, $user->clinic_token, $user->vm_user_id, $user->channel, $user->notification_enabled);
    }

    public static function all(): array
    {
        $users = DB::table('users')->get()->toArray();
        if (empty($users)) {
            return [];
        }
        foreach ($users as $user) {
            $result[] = new User($user->chat_id, $user->clinic_domain, $user->clinic_token, $user->vm_user_id, $user->channel, $user->notification_enabled);
        }
        return $result;
    }
}