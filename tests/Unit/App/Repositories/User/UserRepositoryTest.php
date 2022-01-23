<?php

namespace Tests\Unit\App\Repositories\User;

use Illuminate\Support\Arr;
use App\Models\Payment;
use App\Models\PaymentSum;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\User\UserRepository;
use App\Models\User;

/**
 * 2022/01/23
 * app/Models/User.phpへロジックを移したので今後はtests/Unit/App/Models/UserTest.phpへテストを追加していきます。
 * 今後本テストクラスではメソッドの呼び出しのみテストする様にします。
 */
class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private UserRepository $userRepository;

//    /**
//     * @test
//     */
//    public function getUserWithPaymentSum_指定したidのusersテーブルとpaymentSumリレーションの値が取得できること()
//    {
//        // 検証用usersテーブルとリレーションのレコードを作成し値を変数へ
//        $user = User::factory()->has(PaymentSum::factory())->create();
//        $facPaymentSum = $user->paymentSum->toArray();
//
//        // メソッドの返り値を変数へ
//        $result = $this->userRepository->getUserWithPaymentSum($user->id);
//        $retPaymentSum = $result->paymentSum->toArray();
//
//        // 値が等しいことのテスト
//        $this->assertSame(Arr::get($facPaymentSum, '0.user_id'), Arr::get($retPaymentSum, '0.user_id'));
//        $this->assertSame(Arr::get($facPaymentSum, '0.year'), Arr::get($retPaymentSum, '0.year'));
//        $this->assertSame(Arr::get($facPaymentSum, '0.month'), Arr::get($retPaymentSum, '0.month'));
//        $this->assertSame(Arr::get($facPaymentSum, '0.total_price'), Arr::get($retPaymentSum, '0.total_price'));
//        $this->assertSame(Arr::get($facPaymentSum, '0.created_at'), Arr::get($retPaymentSum, '0.created_at'));
//        $this->assertSame(Arr::get($facPaymentSum, '0.updated_at'), Arr::get($retPaymentSum, '0.updated_at'));
//    }
//
//    /**
//     * @test
//     */
//    public function getUserWithPaymentSum_paymentSumリレーションがyear降順ソートされていること()
//    {
//        $user = User::factory()->has(PaymentSum::factory()->count(3))->create();
//
//        $result = $this->userRepository->getUserWithPaymentSum($user->id);
//        $retPaymentSum = $result->paymentSum->toArray();
//
//        $this->assertTrue(Arr::get($retPaymentSum, '0.year') >= Arr::get($retPaymentSum, '1.year'));
//        $this->assertTrue(Arr::get($retPaymentSum, '1.year') >= Arr::get($retPaymentSum, '2.year'));
//    }
//
//    /**
//     * @test
//     */
//    public function getUserWithPaymentSum_paymentSumリレーションがmonth降順ソートされていること()
//    {
//        // monthの検証のためyearを指定してfactory作成
//        $user = User::factory()->has(PaymentSum::factory(['year' => '2022'])->count(3))->create();
//
//        $result = $this->userRepository->getUserWithPaymentSum($user->id);
//        $retPaymentSum = $result->paymentSum->toArray();
//
//        $this->assertTrue(Arr::get($retPaymentSum, '0.month') >= Arr::get($retPaymentSum, '1.month'));
//        $this->assertTrue(Arr::get($retPaymentSum, '1.month') >= Arr::get($retPaymentSum, '2.month'));
//    }
//
//    /**
//     * @test
//     */
//    public function getUserWithPaymentSum_第2引数が指定されている場合指定された年のpaymentSumが取得できていること()
//    {
//        $year = '2022';
//        $num = 2;
//        $user = User::factory()->has(PaymentSum::factory(['year' => $year])->count($num))->create();
//
//        $result = $this->userRepository->getUserWithPaymentSum($user->id, $year);
//        $this->assertSame($num, $result->paymentSum->count());
//    }
//
//    /**
//     * @test
//     */
//    public function getUserWithPaymentSum_第2引数が指定されるかつ指定された年がDBに存在しない場合空のコレクションが返ってくること()
//    {
//        $user = User::factory()->has(PaymentSum::factory()->count(10))->create();
//
//        $result = $this->userRepository->getUserWithPaymentSum($user->id, '100');
//        $this->assertSame(0, $result->paymentSum->count());
//    }
//
//    /**
//     * @test
//     */
//    public function getUserWithPayments_指定したid_year_monthのusersテーブルとpaymentsリレーションの値が取得できること()
//    {
//        $user = User::factory()->has(Payment::factory()->count(3))->create();
//        $facPayments = $user->payments->toArray();
//
//        $result = $this->userRepository->getUserWithPayments(
//            $user->id,
//            Arr::get($facPayments, '0.year'),
//            Arr::get($facPayments, '0.month')
//        );
//        $retPayments = $result->payments->toArray();
//
//        // 値が等しいことのテスト
//        $this->assertSame(Arr::get($facPayments, '0.category_id'), Arr::get($retPayments, '0.category_id'));
//        $this->assertSame(Arr::get($facPayments, '0.user_id'), Arr::get($retPayments, '0.user_id'));
//        $this->assertSame(Arr::get($facPayments, '0.year'), Arr::get($retPayments, '0.year'));
//        $this->assertSame(Arr::get($facPayments, '0.month'), Arr::get($retPayments, '0.month'));
//        $this->assertSame(Arr::get($facPayments, '0.price'), Arr::get($retPayments, '0.price'));
//        $this->assertSame(Arr::get($facPayments, '0.created_at'), Arr::get($retPayments, '0.created_at'));
//        $this->assertSame(Arr::get($facPayments, '0.updated_at'), Arr::get($retPayments, '0.updated_at'));
//    }
//
//    /**
//     * @test
//     */
//    public function getUserWithPayments_昇順ソートされていること()
//    {
//        // 検証用usersテーブルとリレーションのレコードを作成し値を変数へ
//        $user = User::factory()->has(Payment::factory(['user_id' => '1', 'year' => '2020', 'month' => '1'])->count(3))->create();
//        $facPayments = $user->payments->toArray();
//
//        $result = $this->userRepository->getUserWithPayments(
//            $user->id,
//            Arr::get($facPayments, '0.year'),
//            Arr::get($facPayments, '0.month')
//        );
//        $retPayments = $result->payments->toArray();
//
//        $this->assertTrue(Arr::get($retPayments, '0.category_id') <= Arr::get($retPayments, '1.category_id'));
//        $this->assertTrue(Arr::get($retPayments, '1.category_id') <= Arr::get($retPayments, '2.category_id'));
//    }
//
//    /**
//     * @test
//     */
//    public function getUserWithPaymentsAndSum_指定したidのusersテーブルとpaymentsとpaymentSumリレーションの値が取得できること()
//    {
//        $year = '2022';
//        $month = '1';
//        // paymentSumとpaymentsリレーションをuserテーブル作成時に一緒に作成
//        $user = User::factory()->has(PaymentSum::factory(['year' => $year, 'month' => $month]))
//            ->has(Payment::factory(['year' => $year, 'month' => $month])->count(3))
//            ->create();
//        $facPaymentSum = $user->paymentSum->toArray();
//        $facPayments = $user->payments->toArray();
//
//        $result = $this->userRepository->getUserWithPaymentsAndSum($user->id, $year, $month);
//
//        // paymentSumのテスト
//        $retPaymentSum = $result->paymentSum->toArray();
//        $this->assertSame(Arr::get($facPaymentSum, '0.user_id'), Arr::get($retPaymentSum, '0.user_id'));
//        $this->assertSame(Arr::get($facPaymentSum, '0.year'), Arr::get($retPaymentSum, '0.year'));
//        $this->assertSame(Arr::get($facPaymentSum, '0.month'), Arr::get($retPaymentSum, '0.month'));
//        $this->assertSame(Arr::get($facPaymentSum, '0.total_price'), Arr::get($retPaymentSum, '0.total_price'));
//        $this->assertSame(Arr::get($facPaymentSum, '0.created_at'), Arr::get($retPaymentSum, '0.created_at'));
//        $this->assertSame(Arr::get($facPaymentSum, '0.updated_at'), Arr::get($retPaymentSum, '0.updated_at'));
//
//        // paymentのテスト
//        $retPayments = $result->payments->toArray();
//        foreach ($retPayments as $key => $payment) {
//            $this->assertSame(Arr::get($facPayments, "{$key}.user_id"), Arr::get($payment, 'user_id'));
//            $this->assertSame(Arr::get($facPayments, "{$key}.year"), Arr::get($payment, 'year'));
//            $this->assertSame(Arr::get($facPayments, "{$key}.month"), Arr::get($payment, 'month'));
//            $this->assertSame(Arr::get($facPayments, "{$key}.total_price"), Arr::get($payment, 'total_price'));
//            $this->assertSame(Arr::get($facPayments, "{$key}.created_at"), Arr::get($payment, 'created_at'));
//            $this->assertSame(Arr::get($facPayments, "{$key}.updated_at"), Arr::get($payment, 'updated_at'));
//        }
//    }
}
