<?php

namespace App\Models;

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
     * 複数代入可能な属性
     *
     * @var array
     */
    protected $fillable = ['user_id', 'year', 'month', 'total_price'];

    /**
     * リレーション users_payments belongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * payment_sumsテーブルに年度と月にすでにレコードがある場合はupdate、ない場合はcreateします。
     *
     * @param string $year 年度
     * @param string $month 月
     * @param string $userId ユーザーID
     * @param string $value フォーム入力値
     * @return void
     */
    public static function register(string $year, string $month, string $userId, string $value): void
    {
        self::updateOrCreate(
            ['year' => $year, 'month' => $month, 'user_id' => $userId],
            ['total_price' => $value]
        );
    }

}
