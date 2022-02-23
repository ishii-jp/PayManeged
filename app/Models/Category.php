<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;

class Category extends Model
{
    use HasFactory, CommonScope;

    const CATEGORY_CACHE_KEY = 'payment_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'name',
    ];

    /**
     * リレーション categories_payments hasMany
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * $userId未指定時：DBからカテゴリーを全て取得して返します。
     * $userId指定時：DBからユーザーIDで絞ってカテゴリーを取得して返します。
     * $userId指定時はキャッシュがある場合はキャッシュを返し、なければキャッシュしてから返します。
     *
     * @param string|null $userId
     * @return object
     */
    public static function getCategoryAll(?string $userId = null): object
    {
        if (isset($userId)) {
            $userCacheKey = self::CATEGORY_CACHE_KEY . '_user_id_' . $userId;

            if (Cache::has($userCacheKey)) {
                $categories = Cache::get($userCacheKey);
            } else {
                $categories = self::where('user_id', $userId)->get();
                Cache::put($userCacheKey, $categories);
            }

            return $categories;
        } else {
            return self::all();
        }
    }

    /**
     * 任意のカテゴリー名を返します
     *
     * @param string $id 取得したいカテゴリー名のid
     * @param string|null $userId ユーザーID
     * @return string|null string:カテゴリー名 null:カテゴリー名が存在しない場合。
     */
    public static function getCategoryName(string $id, ?string $userId = null): ?string
    {
        $categories = isset($userId) ? self::getCategoryAll($userId) : self::getCategoryAll();

        // 引数をstringでとっていますが、$categoriesのidはintなので型比較はしないです。
        $ret = $categories->first(function ($value) use ($id) {
            return $value->id == $id;
        });

        return $ret?->name;
    }

    public static function createCategoryName(string $userId, string $categoryName)
    {
        self::updateOrCreate(
            ['user_id' => $userId, 'name' => $categoryName],
            ['name' => $categoryName]
        );
    }

    /**
     * 複数のカテゴリーを更新します。
     *
     * @param string $userId
     * @param array $categoryNames
     * @throws Exception カテゴリーモデルが見つからなかった場合と、取得したカテゴリーのuser_idとログインidが異なる場合にスローされます。
     */
    public static function updateCategories(string $userId, array $categoryNames): void
    {
        foreach ($categoryNames as $id => $categoryName) {
            try {
                $category = self::findOrFail($id);
            } catch (ModelNotFoundException $e) {
                report($e);
                throw new Exception('Category not found $id is ' . $id);
            }

            throw_if(
                $category->user_id != $userId,
                new Exception('Illegal request unmatch user_id id is ' . $id . 'user_id is ' . $userId)
            );

            $category->name = $categoryName;
            $category->save();
        }
    }
}
