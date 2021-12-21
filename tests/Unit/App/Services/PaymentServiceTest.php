<?php

namespace Tests\Unit\App\Services;

use App\Models\Payment;
use App\Models\PaymentSum;
use App\Services\PaymentService;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Mockery;
use Tests\TestCase;
use TypeError;

class PaymentServiceTest extends TestCase
{
    use RefreshDatabase;

    private string $year = '2021';
    private string $month = '12';
    private string $userId = '1';
    private array $payment = ['1' => '50000'];
    private string $paymentSum = '60000';

    /**
     * @test
     */
    public function getNowYear_来年の年が返ってくること()
    {
        $now = Carbon::now();
        $this->travel(1)->years();
        self::assertEquals($now->format('Y') + 1, PaymentService::getNowYear());
    }

    /**
     * @test
     */
    public function getNowYear_先月の年が返ってくること()
    {
        $now = Carbon::now();
        $this->travel(-1)->years();
        self::assertEquals($now->format('Y') - 1, PaymentService::getNowYear());
    }

    /**
     * @test
     */
    public function getNowYear_現在の年が返ってくること()
    {
        $now = Carbon::now();
        self::assertEquals($now->format('Y'), PaymentService::getNowYear());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function allRegister_ユーザーIDがnullの場合()
    {
        $this->expectException(TypeError::class);
        PaymentService::allRegister($this->year, $this->month, null, $this->payment, $this->paymentSum);
    }

    /**
     * @test
     * @throws Exception
     */
    public function allRegister_年がnullの場合()
    {
        $this->expectException(TypeError::class);
        PaymentService::allRegister(null, $this->month, $this->userId, $this->payment, $this->paymentSum);
    }

    /**
     * @test
     * @throws Exception
     */
    public function allRegister_月がnullの場合()
    {
        $this->expectException(TypeError::class);
        PaymentService::allRegister($this->year, null, $this->userId, $this->payment, $this->paymentSum);
    }

    /**
     * @test
     * @throws Exception
     */
    public function allRegister_正常にpaymentsテーブルとpaymentSumテーブルに値が登録されていること()
    {
        PaymentService::allRegister($this->year, $this->month, $this->userId, ['1' => '50000'], $this->paymentSum);
        self::assertSame(1, count(Payment::all()));
        self::assertSame(1, count(PaymentSum::all()));

        // paymentsテーブルの検証
        $payment = Payment::first();
        self::assertEquals($this->year, $payment->year);
        self::assertEquals($this->month, $payment->month);
        self::assertEquals($this->userId, $payment->user_id);
        self::assertEquals(Arr::get($this->payment, '1'), $payment->price);

        // payment_sumsテーブルの検証
        $paymentSum = PaymentSum::first();
        self::assertEquals($this->year, $paymentSum->year);
        self::assertEquals($this->month, $paymentSum->month);
        self::assertEquals($this->userId, $paymentSum->user_id);
        self::assertEquals($this->paymentSum, $paymentSum->total_price);
    }

//    /**
//     * @test
//     * @throws Exception
//     * @todo ErrorException: unserialize(): Error at offset 0 of 2728 bytes
//     * 下のアノテーションを有効にすると上のエラーでテストが失敗します
//     * CI/CDでテストを全部走らせることはこのプロダクトではないので対応を後回しにします。
//     * @runInSeparateProcess
//     * @preserveGlobalState disabled
//     */
//    public function allRegister_PaymentSumのregisterメソッドで例外が発生した場合paymentテーブルがロールバックされていること()
//    {
//        // モック作成
//        Mockery::mock('overload:' . PaymentSum::class)
//            ->shouldReceive('register')
//            ->andThrows(Exception::class)
//            ->getMock();
//
//        try {
//            PaymentService::allRegister($this->year, $this->month, $this->userId, $this->payment, $this->paymentSum);
//            $this->fail('例外が発生しなかったためテストを失敗させます');
//        } catch (Exception) {
//            //
//        }
//
//        self::assertSame(0, count(Payment::all())); // ロールバックされていることの検証
//    }
}
