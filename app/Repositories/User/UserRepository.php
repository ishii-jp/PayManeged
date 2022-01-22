<?php

namespace App\Repositories\User;

use App\Models\User;

/**
 * @todo このクラスのメソッドの処理をモデルに移して呼び出すように修正したい。
 */
class UserRepository implements UserRepositoryInterface
{
    /**
     * 指定したユーザー情報をpaymentSumリレーションを一緒に取得します
     * なお、第二引数に年を指定した場合は指定した年のpaymentSumを一緒に取得します
     *
     * @param string $userId 取得したいユーザーID
     * @param string $year 取得したい年
     * @return object|null
     */
    public function getUserWithPaymentSum(string $userId, string $year = ''): ?object
    {
        return User::with(['paymentSum' => function ($query) use ($year) {
            if ($year === '') {
                $query->orderBy('year', 'DESC')
                    ->orderBy('month', 'DESC');
            } else {
                $query->where('year', $year)
                    ->orderBy('year', 'DESC')
                    ->orderBy('month', 'DESC');
            }
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
            $query->where('year', $year)
                ->where('month', $month)
                ->orderBy('category_id', 'ASC');
        }])
            ->where('id', $userId)
            ->first();
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
        return User::with([
            'payments' => function ($query) use ($year, $month) {
                $query->where('year', $year)
                    ->where('month', $month)
                    ->orderBy('category_id', 'ASC');
            },
            'paymentSum' => function ($query) use ($year, $month) {
                $query->where('year', $year)
                    ->where('month', $month)
                    ->orderBy('year', 'DESC')
                    ->orderBy('month', 'DESC');
            }
        ])
            ->where('id', $userId)
            ->first();
    }
}
