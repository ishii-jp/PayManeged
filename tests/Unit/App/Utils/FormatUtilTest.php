<?php

namespace Tests\Unit\App\Utils;

use App\Utils\FormatUtil;
use Tests\TestCase;

class FormatUtilTest extends TestCase
{
    /**
     * dataproviderの期待値通りの値を返すことのテスト
     *
     * @return void
     * @dataProvider numberFormatDataProvider
     */
    public function testNumberFormat(string $num, ?string $retVal): void
    {
        self::assertSame($retVal, FormatUtil::numberFormat($num));
    }

    /**
     *
     * @return array
     */
    public function numberFormatDataProvider(): array
    {
        return [
            '0の場合' => ['0', '0'],
            '10の場合' => ['10', '10'],
            '100の場合' => ['100', '100'],
            '1000の場合' => ['1000', '1,000'],
            '10000の場合' => ['100000', '100,000'],
            '100000の場合' => ['1000000', '1,000,000'],
            '1000000の場合' => ['10000000', '10,000,000'],
            '10000000の場合' => ['100000000', '100,000,000'],
            '100000000の場合' => ['1000000000', '1,000,000,000'],
            '0.999999の場合' => ['0.999999', null],
            '平仮名の場合' => ['あいうえお', null],
            'カタカナの場合' => ['アイウエオ', null],
            '記号の場合' => ['$#%&', null],
            '半角空文字の場合' => [' ', null],
            '全角空文字の場合' => ['　', null],
        ];
    }
}
