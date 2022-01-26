<?php

namespace App\Services;

use App\Mail\Payment\Notification;
use App\Mail\Payment\NotificationOnePerson;
use App\Models\Payment;
use App\Models\PaymentSum;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
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
     * @todo ハンドリング側でreport()するとエラーログが重複するので、キャッチブロックのreport()を削除する
     */
    public static function allRegister(
        string  $year,
        string  $month,
        ?string $userId,
        ?array  $payment,
        ?string $paymentSum
    ): void
    {
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


    /**
     * $num人当たりの支払い金額を計算して返します
     *
     * @param string|null $paymentSum 支払い合計金額
     * @param string $num 割りたい人数 デフォルト：2
     * @return string|null $number人当たりの支払い金額 小数点以下切り上げ
     */
    public static function calcPayPerPerson(?string $paymentSum, string $num = '2'): ?string
    {
        if (is_null($paymentSum)) {
            return null;
        }

        if (is_numeric($num)) {
            return ceil($paymentSum / $num);
        }

        return null;
    }

    /**
     * 支払い合計金額を計算して返します
     *
     * @param string|null $paymentSum 支払い合計金額
     * @param string|null $fixedCost 固定費 初期値null
     * @return string|null 支払い金額と固定費の合計金額
     */
    public static function calcPaySum(?string $paymentSum, ?string $fixedCost = null): ?string
    {
        if (is_null($paymentSum)) {
            return null;
        }

        if (is_null($fixedCost)) {
            return $paymentSum;
        }

        return $paymentSum + $fixedCost;
    }

    /**
     * 1月から12月の順番で12ヶ月分セットした配列を返します
     * 未入力でデータがpaymentSumsに入っていない月は0をセットします。
     *
     * @param object $paymentSums 任意の年のpayment_sumsコレクション
     * @return array 12ヶ月分の配列
     */
    public static function getMonthlyPaymentSum(object $paymentSums): array
    {
        $monthlyCollect = collect([]);

        for ($num = 1; $num < 13; $num++) {
            if ($paymentSums->contains('month', $num)) {
                $price = $paymentSums->where('month', $num)->map(function ($item) {
                    return $item->total_price;
                });
                $monthlyCollect->push($price->shift());
            } else {
                $monthlyCollect->push(0);
            }
        }

        return $monthlyCollect->toArray();
    }

    /**
     * 通知を送信します。
     *
     * @param string $name
     * @param string $email
     * @param string $year
     * @param string $month
     * @param string $paymentSum
     * @param array $payments
     * @param string|null $numOfPeople
     */
    public static function sendNotification(
        string  $name,
        string  $email,
        string  $year,
        string  $month,
        string  $paymentSum,
        array   $payments,
        ?string $numOfPeople
    ): void {
        // 変動費(入力値)と固定費の合計を計算
        $totalAmount = self::calcPaySum($paymentSum, config('const.fixed_cost'));

        // メール送信
        try {
            if (is_null($numOfPeople)) {
                Mail::to($email)->send(new NotificationOnePerson(
                        $name,
                        $year,
                        $month,
                        $payments,
                        $paymentSum,
                        $totalAmount
                    )
                );
            } else {
                Mail::to($email)->send(new Notification(
                        $name,
                        $year,
                        $month,
                        $payments,
                        $paymentSum,
                        $totalAmount,
                        self::calcPayPerPerson($totalAmount, $numOfPeople)
                    )
                );
            }
        } catch (Exception $e) {
            logger("sendNotification method failed. mail to {$email}");
            report($e);
        }
    }
}
