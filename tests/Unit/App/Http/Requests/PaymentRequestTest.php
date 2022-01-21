<?php

namespace Tests\Unit\App\Http\Requests;

use App\Http\Requests\PaymentRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

/**
 * エラーメッセージを検証するテストは本来必要ですが、ここでは書いていません。
 */
class PaymentRequestTest extends TestCase
{
    /**
     * バリデーションテスト
     *
     * @param array $value 入力値
     * @param boolean $expect 期待値(true:バリデーションOK,false:バリデーションNG)
     * @dataProvider paymentRequestDataProvider
     */
    public function testPaymentRequest(array $value, bool $expect)
    {
        $validator = Validator::make($value, app(PaymentRequest::class)->rules());
        $this->assertSame($expect, $validator->passes());
    }

    /**
     * @return array
     */
    public function paymentRequestDataProvider(): array
    {
        return [
            'payment正常パターン' => [['payment' => ['3500', '50000', '0']], true],
            'paymentSum正常パターン' => [['paymentSum' => '100000'], true],
            'payment-0のパターン' => [['payment' => ['-0', '50000', '0']], false],
            'paymentSum-0のパターン' => [['paymentSum' => '-0'], false],
            'payment負の数混ざり' => [['payment' => ['-1', '50000', '0']], false],
            'paymentSum負の数' => [['paymentSum' => '-1'], false],
            'payment最大値超え混ざり' => [['payment' => ['100000000', '50000', '0']], false],
            'paymentSum最大値超え' => [['paymentSum' => '100000000'], false],
            'payment記号混ざり' => [['payment' => ['!', '50000', '0']], false],
            'paymentSum記号' => [['paymentSum' => '!'], false],
            'payment平仮名混ざり' => [['payment' => ['ああ', '50000', '0']], false],
            'paymentSum平仮名' => [['paymentSum' => 'ああ'], false],
            'paymentカタカナ混ざり' => [['payment' => ['イイ', '50000', '0']], false],
            'paymentSumカタカナ' => [['paymentSum' => 'イイ'], false],
            'peopleNum正常パターン2' => [['peopleNum' => '2'], true],
            'peopleNum正常パターン3' => [['peopleNum' => '3'], true],
            'peopleNum正常パターン4' => [['peopleNum' => '4'], true],
            'peopleNum正常パターン5' => [['peopleNum' => '5'], true],
            'peopleNum0のパターン' => [['peopleNum' => '0'], false],
            'peopleNum-1のパターン' => [['peopleNum' => '-1'], false],
            'peopleNum6のパターン' => [['peopleNum' => '6'], false],
            'peopleひらがなのパターン' => [['peopleNum' => 'あいう'], false],
            'peopleカタカナのパターン' => [['peopleNum' => 'アイウ'], false],
            'people記号のパターン' => [['peopleNum' => '=="'], false],
        ];
    }
}
