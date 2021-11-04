<?php

namespace App\Models;

use App\Models\Payment;
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
}
