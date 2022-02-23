<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, CommonScope;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * リレーション users_payments hasMany
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * リレーション users_payment_sums hasMany
     */
    public function paymentSum()
    {
        return $this->hasMany(PaymentSum::class);
    }

    /**
     * リレーション users_categories hasMany
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    /**
     * 指定したユーザー情報をpaymentSumリレーションを一緒に取得します
     * なお、第二引数に年を指定した場合は指定した年のpaymentSumを一緒に取得します
     *
     * @param string $userId 取得したいユーザーID
     * @param string $year 取得したい年
     * @param bool $getCategory カテゴリー取得するか否かのスイッチ用
     * @return object|null
     */
    public static function getWithPaymentSum(string $userId, string $year = '', bool $getCategory = false): ?object
    {
        $func = [
            'paymentSum' => function ($query) use ($year) {
                if ($year === '') {
                    $query->yearDesc()
                        ->monthDesc();
                } else {
                    $query->ofYear($year)
                        ->yearDesc()
                        ->monthDesc();
                }
            }
        ];

        if ($getCategory) {
            $func = Arr::add($func, 'categories', function ($query) {
                $query->idAsc();
            });
        }

        return self::with($func)->ofId($userId)->first();
    }

    /**
     * 指定したユーザー情報を指定の年、月のpaymentsリレーションを一緒に取得します
     *
     * @param string $userId 取得したいユーザーID
     * @param string $year 取得したい年
     * @param string $month 取得したい月
     * @return object|null
     */
    public static function getWithPayments(string $userId, string $year, string $month): ?object
    {
        return self::with(['payments' => function ($query) use ($year, $month) {
            $query->ofYear($year)
                ->ofMonth($month)
                ->categoryIdAsc();
        }])
            ->ofId($userId)
            ->first();
    }

    /**
     * 指定したユーザー情報と指定のカテゴリーidに合致するpaymentsリレーションを一緒に取得します
     * なお、第3引数に年を指定した場合は指定した年のpaymentsを取得します
     *
     * @param string $userId 取得したいユーザーID
     * @param string $categoryId 取得したいカテゴリーID
     * @param string $year 取得したい年度
     * @return object|null
     */
    public static function getWithPaymentsByCategoryId(string $userId, string $categoryId, string $year = ''): ?object
    {
        return self::with(['payments' => function ($query) use ($categoryId, $year) {
            if ($year === '') {
                $query->ofCategoryId($categoryId)
                    ->yearDesc()
                    ->monthDesc();
            } else {
                $query->ofCategoryId($categoryId)
                    ->ofYear($year)
                    ->yearDesc()
                    ->monthDesc();
            }
        }])
            ->ofId($userId)
            ->first();
    }

    /**
     * 指定したユーザー情報を指定の年、月のpaymentsとpaymentSumリレーションを一緒に取得します
     *
     * @param string $userId 取得したいユーザーID
     * @param string $year 取得したい年
     * @param string $month 取得したい月
     * @return object|null
     */
    public static function getWithPaymentsAndSum(string $userId, string $year, string $month): ?object
    {
        return self::with([
            'payments' => function ($query) use ($year, $month) {
                $query->ofYear($year)
                    ->ofMonth($month)
                    ->categoryIdAsc();
            },
            'paymentSum' => function ($query) use ($year, $month) {
                $query->ofYear($year)
                    ->ofMonth($month)
                    ->yearDesc()
                    ->monthDesc();
            }
        ])
            ->ofId($userId)
            ->first();
    }
}
