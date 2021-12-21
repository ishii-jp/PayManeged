<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\PaymentSum;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use TypeError;

class PaymentService
{
    /**
     * 現在の年を返します。
     *
     * @return string 現在の年
     */
    public static function getNowYear(): string
    {
        $now = Carbon::now();
        return $now->format('Y');
    }

    /**
     * トランザクションをはりpaymentsテーブルとpayment_sumsテーブルへ登録を行います。
     *
     * 例外が発生した場合は呼び出し元へ投げ返します。
     * 本メソッドを呼び出す際はハンドリングを行ってください。
     *
     * @param string $year
     * @param string $month
     * @param string|null $userId
     * @param array|null $payment
     * @param string|null $paymentSum
     * @throws Exception|TypeError
     */
    public static function allRegister(
        string  $year,
        string  $month,
        ?string $userId,
        ?array  $payment,
        ?string $paymentSum
    ): void {
        try {
            DB::transaction(function () use ($year, $month, $userId, $payment, $paymentSum) {
                Payment::register($year, $month, $userId, $payment);
                PaymentSum::register($year, $month, $userId, $paymentSum);
            });
        } catch (Exception | TypeError $e) {
            report($e);
            throw $e;
        }
    }
}
