<?php

namespace Tests\Unit\App\Models;

use App\Models\Category;
use Illuminate\Support\Arr;
use App\Models\Payment;
use App\Models\PaymentSum;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function getWithPaymentSum_指定したidのusersテーブルとpaymentSumリレーションの値が取得できること()
    {
        // 検証用usersテーブルとリレーションのレコードを作成し値を変数へ
        $user = User::factory()->has(PaymentSum::factory())->create();
        $facPaymentSum = $user->paymentSum->toArray();

        // メソッドの返り値を変数へ
        $result = User::getWithPaymentSum($user->id);
        $retPaymentSum = $result->paymentSum->toArray();

        // 値が等しいことのテスト
        $this->assertSame(Arr::get($facPaymentSum, '0.user_id'), Arr::get($retPaymentSum, '0.user_id'));
        $this->assertSame(Arr::get($facPaymentSum, '0.year'), Arr::get($retPaymentSum, '0.year'));
        $this->assertSame(Arr::get($facPaymentSum, '0.month'), Arr::get($retPaymentSum, '0.month'));
        $this->assertSame(Arr::get($facPaymentSum, '0.total_price'), Arr::get($retPaymentSum, '0.total_price'));
        $this->assertSame(Arr::get($facPaymentSum, '0.created_at'), Arr::get($retPaymentSum, '0.created_at'));
        $this->assertSame(Arr::get($facPaymentSum, '0.updated_at'), Arr::get($retPaymentSum, '0.updated_at'));
    }

    /**
     * @test
     */
    public function getWithPaymentSum_paymentSumリレーションがyear降順ソートされていること()
    {
        $user = User::factory()->has(PaymentSum::factory()->count(3))->create();

        $result = User::getWithPaymentSum($user->id);
        $retPaymentSum = $result->paymentSum->toArray();

        $this->assertTrue(Arr::get($retPaymentSum, '0.year') >= Arr::get($retPaymentSum, '1.year'));
        $this->assertTrue(Arr::get($retPaymentSum, '1.year') >= Arr::get($retPaymentSum, '2.year'));
    }

    /**
     * @test
     */
    public function getWithPaymentSum_paymentSumリレーションがmonth降順ソートされていること()
    {
        // monthの検証のためyearを指定してfactory作成
        $user = User::factory()->has(PaymentSum::factory(['year' => '2022'])->count(3))->create();

        $result = User::getWithPaymentSum($user->id);
        $retPaymentSum = $result->paymentSum->toArray();

        $this->assertTrue(Arr::get($retPaymentSum, '0.month') >= Arr::get($retPaymentSum, '1.month'));
        $this->assertTrue(Arr::get($retPaymentSum, '1.month') >= Arr::get($retPaymentSum, '2.month'));
    }

    /**
     * @test
     */
    public function getWithPaymentSum_第2引数が指定されている場合指定された年のpaymentSumが取得できていること()
    {
        $year = '2022';
        $num = 2;
        $user = User::factory()->has(PaymentSum::factory(['year' => $year])->count($num))->create();

        $result = User::getWithPaymentSum($user->id, $year);
        $this->assertSame($num, $result->paymentSum->count());
    }

    /**
     * @test
     */
    public function getWithPaymentSum_第2引数が指定されるかつ指定された年がDBに存在しない場合空のコレクションが返ってくること()
    {
        $user = User::factory()->has(PaymentSum::factory()->count(1))->create();

        $result = User::getWithPaymentSum($user->id, '100');
        $this->assertSame(0, $result->paymentSum->count());
    }

    /**
     * @test
     */
    public function getWithPaymentSum_getCategoryにtrueをセットした時カテゴリーリレーションがid昇順で取得できること()
    {
        $user = User::factory()->has(PaymentSum::factory())->has(Category::factory()->count(2))->create();

        $result = User::getWithPaymentSum($user->id, '', true);

        $this->assertArrSortAsc($result->categories->toArray());
    }

    /**
     * @test
     */
    public function getWithPayments_指定したid_year_monthのusersテーブルとpaymentsリレーションの値が取得できること()
    {
        $user = User::factory()->has(Payment::factory()->count(3))->create();
        $facPayments = $user->payments->toArray();

        $result = User::getWithPayments(
            $user->id,
            Arr::get($facPayments, '0.year'),
            Arr::get($facPayments, '0.month')
        );
        $retPayments = $result->payments->toArray();

        // 値が等しいことのテスト
        $this->assertSame(Arr::get($facPayments, '0.category_id'), Arr::get($retPayments, '0.category_id'));
        $this->assertSame(Arr::get($facPayments, '0.user_id'), Arr::get($retPayments, '0.user_id'));
        $this->assertSame(Arr::get($facPayments, '0.year'), Arr::get($retPayments, '0.year'));
        $this->assertSame(Arr::get($facPayments, '0.month'), Arr::get($retPayments, '0.month'));
        $this->assertSame(Arr::get($facPayments, '0.price'), Arr::get($retPayments, '0.price'));
        $this->assertSame(Arr::get($facPayments, '0.created_at'), Arr::get($retPayments, '0.created_at'));
        $this->assertSame(Arr::get($facPayments, '0.updated_at'), Arr::get($retPayments, '0.updated_at'));
    }

    /**
     * @test
     */
    public function getWithPayments_昇順ソートされていること()
    {
        // 検証用usersテーブルとリレーションのレコードを作成し値を変数へ
        $user = User::factory()->has(Payment::factory(['user_id' => '1', 'year' => '2020', 'month' => '1'])->count(3))->create();
        $facPayments = $user->payments->toArray();

        $result = User::getWithPayments(
            $user->id,
            Arr::get($facPayments, '0.year'),
            Arr::get($facPayments, '0.month')
        );
        $retPayments = $result->payments->toArray();

        $this->assertTrue(Arr::get($retPayments, '0.category_id') <= Arr::get($retPayments, '1.category_id'));
        $this->assertTrue(Arr::get($retPayments, '1.category_id') <= Arr::get($retPayments, '2.category_id'));
    }

    /**
     * @test
     */
    public function getWithPaymentsAndSum_指定したidのusersテーブルとpaymentsとpaymentSumリレーションの値が取得できること()
    {
        $year = '2022';
        $month = '1';
        // paymentSumとpaymentsリレーションをuserテーブル作成時に一緒に作成
        $user = User::factory()->has(PaymentSum::factory(['year' => $year, 'month' => $month]))
            ->has(Payment::factory(['year' => $year, 'month' => $month])->count(3))
            ->create();
        $facPaymentSum = $user->paymentSum->toArray();
        $facPayments = $user->payments->toArray();

        $result = User::getWithPaymentsAndSum($user->id, $year, $month);

        // paymentSumのテスト
        $retPaymentSum = $result->paymentSum->toArray();
        $this->assertSame(Arr::get($facPaymentSum, '0.user_id'), Arr::get($retPaymentSum, '0.user_id'));
        $this->assertSame(Arr::get($facPaymentSum, '0.year'), Arr::get($retPaymentSum, '0.year'));
        $this->assertSame(Arr::get($facPaymentSum, '0.month'), Arr::get($retPaymentSum, '0.month'));
        $this->assertSame(Arr::get($facPaymentSum, '0.total_price'), Arr::get($retPaymentSum, '0.total_price'));
        $this->assertSame(Arr::get($facPaymentSum, '0.created_at'), Arr::get($retPaymentSum, '0.created_at'));
        $this->assertSame(Arr::get($facPaymentSum, '0.updated_at'), Arr::get($retPaymentSum, '0.updated_at'));

        // paymentのテスト
        $retPayments = $result->payments->toArray();
        foreach ($retPayments as $key => $payment) {
            $this->assertSame(Arr::get($facPayments, "{$key}.user_id"), Arr::get($payment, 'user_id'));
            $this->assertSame(Arr::get($facPayments, "{$key}.year"), Arr::get($payment, 'year'));
            $this->assertSame(Arr::get($facPayments, "{$key}.month"), Arr::get($payment, 'month'));
            $this->assertSame(Arr::get($facPayments, "{$key}.total_price"), Arr::get($payment, 'total_price'));
            $this->assertSame(Arr::get($facPayments, "{$key}.created_at"), Arr::get($payment, 'created_at'));
            $this->assertSame(Arr::get($facPayments, "{$key}.updated_at"), Arr::get($payment, 'updated_at'));
        }
    }

    /**
     * @test
     */
    public function getWithPaymentsByCategoryId_指定したuserIdのusersテーブルとcategoryIdで絞ったpaymentsリレーションの値が取得できること()
    {
        $categoryId = '1';
        $user = User::factory()->has(Payment::factory(['category_id' => $categoryId])->count(2))->create();

        $result = User::getWithPaymentsByCategoryId($user->id, $categoryId);

        $this->assertArrSortAsc($result->payments->toArray());
    }

    /**
     * @test
     */
    public function getWithPaymentsByCategoryId_指定したuserIdが存在しない場合nullを返すること()
    {
        self::assertNull(User::getWithPaymentsByCategoryId('999999', '999999'));
    }

    /**
     * @test
     */
    public function getWithPaymentsByCategoryId_存在しないcategoryIdが指定されたらnull()
    {
        $user = User::factory()->has(Payment::factory(['category_id' => '1'])->count(3))->create();

        $result = User::getWithPaymentsByCategoryId($user->id, '99999999');

        self::assertTrue($result->payments->isEmpty());
    }

    /**
     * @test
     */
    public function getWithPaymentsByCategoryId_yearを設定した場合設定したyearのデータを取得できること()
    {
        $categoryId = '1';
        $year = '2022';
        // 取得できちゃいけないレコードを作成
        User::factory()->has(Payment::factory(['category_id' => $categoryId, 'year' => '2021']))->create();
        User::factory()->has(Payment::factory(['category_id' => $categoryId, 'year' => '2020']))->create();
        // 取得できなきゃいけないレコードを作成
        $user = User::factory()
            ->has(Payment::factory(['category_id' => $categoryId, 'year' => $year])->count(2))
            ->create();

        $result = User::getWithPaymentsByCategoryId($user->id, $categoryId, $year);

        // $year年度のものしか取得されていないことのテスト
        $result->payments->map(function ($item) use ($year) {
            self::assertTrue($item->year === (int)$year);
        });

        // ソートのテスト
        $this->assertArrSortAsc($result->payments->toArray());
    }

    /**
     * @test
     */
    public function getWithPaymentsByCategoryId_yearを設定した場合かつ存在しないyearの場合空コレクションが返ってくること()
    {
        $categoryId = '1';
        $user = User::factory()->has(Payment::factory(['category_id' => $categoryId, 'year' => '2022']))->create();

        $result = User::getWithPaymentsByCategoryId($user->id, $categoryId, '2020');

        self::assertTrue($result->payments->isEmpty());
    }

    /**
     * 配列の要素を前後で確認し昇順ソートされていることをテストします。
     *
     * @param array $arr テスト対象の配列
     */
    private function assertArrSortAsc(array $arr): void
    {
        array_reduce(
            $arr,
            function ($carry, $item) {
                // idが昇順ソートされていることをテスト
                $this->assertTrue($carry < $item);
            },
            0
        );
    }
}
