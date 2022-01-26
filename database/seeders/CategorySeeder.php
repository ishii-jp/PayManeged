<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

/**
 * CategorySeeder class
 *
 * categoriesテーブルにサンプルデータを登録します
 * ユーザーがすでにいたらユーザー分カテゴリーを作成。いなければ固定値のユーザーIDで作成
 *
 * 実行コマンド
 * php artisan db:seed --class=CategorySeeder
 *
 * @todo 2022/01/25 categoriesのカラムにuser_idを追加したため本クラスを修正。　修正後のテストはしてないです。
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
        $users = DB::table('users')->get();
        $categories = [
            '食費 + 雑費 + 電気代',
            'ガス使用料',
            '水道使用料'
        ];

        if ($users->isEmpty()) {
            foreach ($users as $user) {
                foreach ($categories as $category) {
                    DB::table('categories')->insert([
                        'user_id' => $user->id,
                        'name' => $category
                    ]);
                }
            }
        } else {
            foreach ($categories as $category) {
                DB::table('categories')->insert([
                    'user_id' => '1',
                    'name' => $category
                ]);
            }
        }
    }
}
