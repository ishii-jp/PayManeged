<?php

namespace App\Utils;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class CacheUtil
{
    /**
     * $userIdのユーザーのカテゴリーキャッシュを削除します
     *
     * @param string $userId
     */
    public static function categoryCacheDelete(string $userId): void
    {
        Cache::forget(Category::CATEGORY_CACHE_KEY . '_user_id_' . $userId);
    }
}
