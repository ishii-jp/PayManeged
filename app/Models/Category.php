<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * リレーション categories_payments hasMany
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * DBからカテゴリーを全て取得して返します。
     *
     * @return object
     */
    public static function getCategoryAll(): object
    {
        return self::all();
    }

    /**
     * 任意のカテゴリー名を返します
     *
     * @param string 取得したいカテゴリー名のid
     * @return string|null string:カテゴリー名 null:カテゴリー名が存在しない場合。
     * @todo カテゴリーを取得したらキャッシュするようにして何度メソッド呼び出してもクエリー1回で済むようにしたい。
     */
    public static function getCategoryName(string $id): ?string
    {
        $category = self::where('id', $id)->first();
        return $category->name ?? null;
    }
}
