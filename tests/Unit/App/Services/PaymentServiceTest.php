<?php

namespace Tests\Unit\App\Services;

use App\Mail\Payment\Notification;
use App\Mail\Payment\NotificationOnePerson;
use App\Models\Payment;
use App\Models\PaymentSum;
use App\Services\PaymentService;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
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

    /**
     * メソッドの返り値がdataproviderの期待値通りであることのテスト
     *
     * @param string|null $paymentSum
     * @param string|null $fixedCost
     * @param string|null $retVal
     * @return void
     * @dataProvider calcPaySumDataProvider
     */
    public function testCalcPaySum(?string $paymentSum, ?string $fixedCost, ?string $retVal): void
    {
        self::assertSame($retVal, PaymentService::calcPaySum($paymentSum, $fixedCost));
    }

    /**
     * calcPaySum()用データプロバイダー
     *
     * @return array
     */
    public function calcPaySumDataProvider(): array
    {
        return [
            'paymentSumがnullかつfixedCostが整数の場合' => [null, '81017', null],
            'paymentSumが整数かつfixedCostがnullの場合' => ['81017', null, '81017'],
            'paymentSumが整数かつfixedCostが整数の場合' => ['110021', '81017', '191038']
        ];
    }

    /**
     * メソッドの返り値がdataproviderの期待値通りであることのテスト
     *
     * @param string|null $paymentSum
     * @param string $retVal
     * @return void
     * @dataProvider calcPayPerPersonDataProvider
     */
    public function testCalcPayPerPerson(?string $paymentSum, ?string $retVal): void
    {
        self::assertSame($retVal, PaymentService::calcPayPerPerson($paymentSum));
    }

    /**
     * calcPayPerPerson()用データプロバイダー
     *
     * @return array
     */
    public function calcPayPerPersonDataProvider(): array
    {
        return [
            'paymentSumがnullの場合' => [null, null],
            'paymentSumが整数の場合' => ['168292', '84146']
        ];
    }

    /**
     * @test
     * @dataProvider calcPayPerPersonSecondArgDataProvider
     */
    public function calcPaySum_第二引数を指定された場合指定された数で計算すること(string $paymentSum, string $num, ?string $retVal): void
    {
        self::assertSame($retVal, PaymentService::calcPayPerPerson($paymentSum, $num));
    }

    /**
     * calcPayPerPerson()第二引数テスト用データプロバイダー
     *
     * @return array
     */
    public function calcPayPerPersonSecondArgDataProvider(): array
    {
        return [
            '第二引数$numが3の場合' => ['191038', '3', '63680'],
            '第二引数$numが4の場合' => ['191038', '4', '47760'],
            '第二引数$numが2.5の場合' => ['191038', '2.5', '76416'],
            '第二引数$numが平仮名の場合' => ['191038', 'あいうえお', null],
            '第二引数$numがカタカナの場合' => ['191038', 'アイウエオ', null],
            '第二引数$numが記号の場合' => ['191038', '$%&&"', null],
            '第二引数$numが半角スペースの場合' => ['191038', ' ', null],
            '第二引数$numが全角スペースの場合' => ['191038', '　', null]
        ];
    }

    /**
     * @test
     */
    public function getMonthlyPaymentSum_空のコレクションが引数に渡された場合返り値配列の要素が全て0になっていること()
    {
        $argObj = PaymentSum::all();
        $expected = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

        self::assertSame($expected, PaymentService::getMonthlyPaymentSum($argObj));
    }

    /**
     * @test
     */
    public function getMonthlyPaymentSum_返り値配列の要素が入力されていない月は0がセットされていること()
    {
        $price = 50000;
        PaymentSum::factory()->create(['month' => '7', 'total_price' => '50000']);
        $argObj = PaymentSum::all();
        $expected = [0, 0, 0, 0, 0, 0, $price, 0, 0, 0, 0, 0];

        self::assertSame($expected, PaymentService::getMonthlyPaymentSum($argObj));
    }

    /**
     * @test
     */
    public function getMonthlyPaymentSum_12ヶ月分全てデータがある場合返り値配列の要素全てにデータが入っていること()
    {
        $price = 50000;
        for ($n = 1; $n < 13; $n++) {
            PaymentSum::factory()->create(['month' => $n, 'total_price' => $price]);
            $price++;
        }

        $argObj = PaymentSum::all();
        $expected = [50000, 50001, 50002, 50003, 50004, 50005, 50006, 50007, 50008, 50009, 50010, 50011];

        self::assertSame($expected, PaymentService::getMonthlyPaymentSum($argObj));
    }

    /**
     * @test
     */
    public function getMonthlyPaymentSum_retPaymentをtrueにした場合12ヶ月分全てデータがある場合返り値配列の要素全てにデータが入っていること()
    {
        $price = 50000;
        for ($n = 1; $n < 13; $n++) {
            Payment::factory()->create(['month' => $n, 'price' => $price]);
            $price++;
        }

        $argObj = Payment::all();
        $expected = [50000, 50001, 50002, 50003, 50004, 50005, 50006, 50007, 50008, 50009, 50010, 50011];

        self::assertSame($expected, PaymentService::getMonthlyPaymentSum($argObj, true));
    }

    /**
     * @test
     * @dataProvider sendNotificationDataProvider
     */
    public function sendNotification_期待通りにメールを送り分けていること(
        ?string $num,
        string  $mailClass
    )
    {
        // 実際にはメールを送らないように設定
        Mail::fake();

        $name = 'test user';
        $email = 'test@example.co.jp';
        $year = '2022';
        $month = '1';
        $payments = ['1' => '100000', '2' => '3000', '3' => '4000'];
        $paymentSum = '107000';
        $numOfPeople = $num;

        PaymentService::sendNotification($name, $email, $year, $month, $paymentSum, $payments, $numOfPeople);

        // メッセージが指定したユーザーに届いたことをアサート
        Mail::assertSent($mailClass, function ($mail) use ($email) {
            return $mail->hasTo($email);
        });

        // メールが1回送信されたことをアサート
        Mail::assertSent($mailClass, 1);
    }

    public function sendNotificationDataProvider(): array
    {
        return [
            'numOfPeopleがnullの場合NotificationOnePersonのメールが送信されること' => [
                null,
                NotificationOnePerson::class
            ],
            'numOfPeopleが2の場合Notificationのメールが送信されること' => [
                '2',
                Notification::class
            ],
            'numOfPeopleが3の場合Notificationのメールが送信されること' => [
                '3',
                Notification::class
            ],
            'numOfPeopleが4の場合Notificationのメールが送信されること' => [
                '4',
                Notification::class
            ],
            'numOfPeopleが5の場合Notificationのメールが送信されること' => [
                '5',
                Notification::class
            ],
        ];
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
