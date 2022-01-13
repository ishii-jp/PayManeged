<?php

namespace Tests\Unit\App\Utils;

use App\Utils\RequestUtil;
use Tests\TestCase;

class RequestUtilTest extends TestCase
{
    /**
     * リクエストパラメータyearのテスト
     * 
     * @test
     * @param string $reqParam メソッド引数の文字列
     * @param bool $retVal メソッド返り値の期待値
     * @dataProvider RequestParamValidDataProvider
     */
    public function RequestParamValid_期待通りの返り値を返すこと(string $reqParam, bool $retVal): void
    {
        $this->assertSame($retVal, RequestUtil::RequestParamValid($reqParam, config('match.paramYear')));
    }

    /**
     *
     * @return array
     */
    public function RequestParamValidDataProvider(): array
    {
        return [
            '正規表現にマッチする場合_1999' => ['1999', true],
            '正規表現にマッチする場合_2999' => ['2999', true],
            '正規表現にマッチしない場合_3999' => ['3999', false],
            '正規表現にマッチしない場合_900' => ['900', false],
            '正規表現にマッチしない場合_90' => ['90', false],
            '正規表現にマッチしない場合_9' => ['9', false]
        ];
    }
}
