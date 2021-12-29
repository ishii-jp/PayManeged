<?php

namespace App\Utils;

use Exception;

class FormatUtil
{
    /**
     * ナンバーフォーマットをかけて返します。
     * 
     * 数値以外の値や例外発生時はnullを返します。
     *
     * @param string $num 数値文字列
     * @return string|null フォーマット後の文字列
     */
    public static function numberFormat(string $num): ?string
    {
        if(!is_numeric($num)) {
            return null;
        }

        try {
            return number_format($num);
        } catch (Exception $e) {
            report($e);
            return null;
        }
    }
}
