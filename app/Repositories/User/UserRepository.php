<?php

namespace App\Repositories\User;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    /**
     * 名前で1レコードを取得(検証用)
     *
     * @param string $userId 取得したいユーザーID
     * @return object|null
     * @todo
     */
    public function getUser(string $userId): ?object
    {
        return User::find($userId);
    }
}
