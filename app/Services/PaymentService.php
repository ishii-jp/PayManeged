<?php

namespace App\Services;

use Carbon\Carbon;

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
}
