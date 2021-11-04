<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

/**
 * CategorySeeder class
 *
 * categoriesテーブルにサンプルデータを登録します
 * 実行コマンド
 * php artisan db:seed --class=CategorySeeder
 */
class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            '食費 + 雑費 + 電気代',
            'ガス使用料',
            '水道使用料'
        ];

        foreach($categories as $category){
            DB::table('categories')->insert([
                'name' => $category
            ]);
        }
    }
}
