<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Category extends Model
{
    use HasFactory;

    const CATEGORY_CACHE_KEY = 'payment_categories';

    /**
     * リレーション categories_payments hasMany
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * DBからカテゴリーを全て取得して返します。
     * キャッシュがある場合はキャッシュを返し、なければキャッシュしてから返します。
     *
     * @return object
     */
    public static function getCategoryAll(): object
    {
        if (Cache::has(self::CATEGORY_CACHE_KEY)) {
            $categories = Cache::get(self::CATEGORY_CACHE_KEY);
        } else {
            $categories = self::all();
            Cache::put(self::CATEGORY_CACHE_KEY, $categories);
        }

        return $categories;
    }

    /**
     * 任意のカテゴリー名を返します
     *
     * @param string 取得したいカテゴリー名のid
     * @return string|null string:カテゴリー名 null:カテゴリー名が存在しない場合。
     */
    public static function getCategoryName(string $id): ?string
    {
        $categories = self::getCategoryAll();

        // 引数をstringでとっているけど、$categoriesのidはintなので型比較はしないです。
        $ret = $categories->first(function ($value) use ($id) {
            return $value->id == $id;
        });

        return $ret?->name;
    }
}
