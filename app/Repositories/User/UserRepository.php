<?php

namespace App\Repositories\User;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    /**
     * 指定したユーザー情報をpaymentSumリレーションを一緒に取得します
     *
     * @param string $userId 取得したいユーザーID
     * @return object|null
     */
    public function getUserWithPaymentSum(string $userId): ?object
    {
        return User::with(['paymentSum' => function ($query) {
            $query->orderBy('year', 'DESC')->orderBy('month', 'DESC');
        }])
        ->where('id', $userId)
        ->first();
    }

    /**
     * 指定したユーザー情報を指定の年、月のpaymentsリレーションを一緒に取得します
     *
     * @param string $userId 取得したいユーザーID
     * @param string $year 取得したい年
     * @param string $month 取得したい月
     * @return object|null
     */
    public function getUserWithPayments(string $userId, string $year, string $month): ?object
    {
        return User::with(['payments' => function ($query) use ($year, $month) {
            $query->where('year', $year)->where('month', $month)->orderBy('category_id', 'ASC');
        }])
        ->where('id', $userId)
        ->first();
    }
}
