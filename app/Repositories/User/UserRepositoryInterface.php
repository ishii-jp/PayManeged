<?php

namespace App\Repositories\User;

interface UserRepositoryInterface
{
    /**
     * ユーザー情報を取得します
     *
     * @param string $userId 取得したいユーザーID
     * @return object|null
     */
    public function getUser(string $userId): ?object;
}