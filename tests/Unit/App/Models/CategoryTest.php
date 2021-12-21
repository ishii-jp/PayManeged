<?php

namespace Tests\Unit\App\Models;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

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

}
