<?php

namespace App\Repositories\User;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    /**
     * 指定したユーザー情報をpaymentSumリレーションを一緒に取得します
     * なお、第二引数に年を指定した場合は指定した年のpaymentSumを一緒に取得します
     *
     * @param string $userId 取得したいユーザーID
     * @param string $year 取得したい年
     * @param bool $getCategory カテゴリー取得するか否かのスイッチ用
     * @return object|null
     */
    public function getUserWithPaymentSum(string $userId, string $year = '', bool $getCategory = false): ?object
    {
        return User::getWithPaymentSum($userId, $year, $getCategory);
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
        return User::getWithPayments($userId, $year, $month);
    }

    /**
     * 指定したユーザー情報を指定の年、月のpaymentsとpaymentSumリレーションを一緒に取得します
     *
     * @param string $userId 取得したいユーザーID
     * @param string $year 取得したい年
     * @param string $month 取得したい月
     * @return object|null
     */
    public function getUserWithPaymentsAndSum(string $userId, string $year, string $month): ?object
    {
        return User::getWithPaymentsAndSum($userId, $year, $month);
    }

    /**
     * 指定したユーザー情報と指定のカテゴリーidに合致するpaymentsリレーションを一緒に取得します
     *
     * @param string $userId 取得したいユーザーID
     * @param string $categoryId 取得したいカテゴリーID
     * @param string $year 取得したい年度
     * @return object|null
     */
    public function getWithPaymentsByCategoryId(string $userId, string $categoryId, string $year = ''): ?object
    {
        return user::getWithPaymentsByCategoryId($userId, $categoryId, $year);
    }
}
