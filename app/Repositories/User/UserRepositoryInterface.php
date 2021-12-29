<?php

namespace App\Repositories\User;

interface UserRepositoryInterface
{
    /**
     * 指定したユーザー情報をpaymentSumリレーションを一緒に取得します
     *
     * @param string $userId 取得したいユーザーID
     * @return object|null
     */
    public function getUserWithPaymentSum(string $userId): ?object;

    /**
     * 指定したユーザー情報を指定の年、月のpaymentsリレーションを一緒に取得します
     *
     * @param string $userId 取得したいユーザーID
     * @param string $year 取得したい年
     * @param string $month 取得したい月
     * @return object|null
     */
    public function getUserWithPayments(string $userId, string $year, string $month): ?object;
}