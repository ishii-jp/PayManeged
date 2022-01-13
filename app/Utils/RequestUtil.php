<?php

namespace App\Utils;

class RequestUtil
{
    /**
     * $paramNameが$regに一致するか判定します
     *
     * @param string $reqParam
     * @param string $reg
     * @return bool true:一致 false:不一致
     */
    public static function RequestParamValid(string $reqParam, string $reg): bool
    {
        if (preg_match("/{$reg}/", $reqParam)) {
            return true;
        }
        return false;
    }
}
