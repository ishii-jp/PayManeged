<?php

namespace App\Models;

use App\Models\User;
use App\Models\Category;
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
    use HasFactory;

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
}
