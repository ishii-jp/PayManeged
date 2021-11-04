<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @todo usersテーブルからレコードを削除したらリレーションで紐づくテーブルのレコードを削除するようにする
 * https://readouble.com/laravel/8.x/ja/eloquent.html
 * deletingイベントで処理することで実現できそう。
 * 無理だったら外部キー制約でCASCADEを設定するしかなさそう
 */
class PaymentSum extends Model
{
    use HasFactory;

    /**
     * リレーション users_payments belongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
