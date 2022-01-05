<?php

namespace Tests\Unit\App\Models;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        // DB接続先がテスト用に切り替わっているか確認
        // dd(env('APP_ENV'), env('DB_DATABASE'), env('DB_CONNECTION'));
    }

    /**
     * @test
     */
    public function getCategoryAll_テーブルにレコードが1件も存在しない場合空のコレクションが返ってくること(): void
    {
        $category = Category::all();
        $this->assertTrue($category->isEmpty());
    }

    /**
     * @test
     */
    public function getCategoryAll_テーブルにレコードが1件も存在しない場合countすると0になること(): void
    {
        self::assertSame(0, count(Category::all()));
    }

    /**
     * @test
     */
    public function getCategoryAll_テーブルにレコードがある場合全てのレコードが返ってくること(): void
    {
        $num = 4;
        Category::factory($num)->create();
        self::assertSame($num, count(Category::all()));
    }

    /**
     * @test
     */
    public function getCategoryName_categoriesテーブに存在しないidを指定された場合nullを返すこと()
    {
        $id = '90000'; // 存在しないid
        self::assertNull(Category::getCategoryName($id));
    }

    /**
     * @test
     */
    public function getCategoryName_categoriesテーブに存在するidを指定された場合該当するカテゴリー名を返すこと()
    {
        $id = '1';
        $categoryName = 'test category 1';
        Category::factory()->create(['id' => $id, 'name' => $categoryName]);

        self::assertSame($categoryName, Category::getCategoryName($id));
    }
}
