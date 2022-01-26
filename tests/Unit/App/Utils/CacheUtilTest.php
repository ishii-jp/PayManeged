<?php

namespace Tests\Unit\App\Utils;

use App\Models\Category;
use App\Utils\CacheUtil;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CacheUtilTest extends TestCase
{
    /**
     * @test
     */
    public function CacheUtil_キャッシュが削除されていること()
    {
        $userId = '1';
        $key = Category::CATEGORY_CACHE_KEY . '_user_id_' . $userId;
        Cache::put($key, 'cache test');

        self::assertTrue(Cache::has($key)); // キャッシュが作成されたことの確認

        CacheUtil::categoryCacheDelete($userId);

        self::assertFalse(Cache::has($key)); // キャッシュが削除されたことのテスト
    }
}
