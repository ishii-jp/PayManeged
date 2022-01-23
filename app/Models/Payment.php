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
class Payment extends Model
{
    use HasFactory, CommonScope;

    /**
     * 複数代入可能な属性
     *
     * @var array
     */
    protected $fillable = ['category_id', 'user_id', 'year', 'month', 'price'];

    /**
     * リレーション payments_users belongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * リレーション payments_categories belongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * paymentsテーブルに年度と月にすでにレコードがある場合はupdate、ない場合はcreateします。
     *
     * @param string $year 年度
     * @param string $month 月
     * @param string $userId ユーザーID
     * @param array $values フォーム入力値
     * @return void
     */
    public static function register(string $year, string $month, string $userId, array $values): void
    {
        foreach ($values as $categoryId => $price) {
            self::updateOrCreate(
                ['year' => $year, 'month' => $month, 'user_id' => $userId, 'category_id' => $categoryId],
                ['price' => $price]
            );
        }
    }
}
