<?php

namespace Tests\Unit\App\Http\Requests;

use App\Http\Requests\CategoryPutRequest;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CategoryPutRequestTest extends TestCase
{
    /**
     * CategoryPutRequestではprepareForValidation()で全角スペースを半角スペースに変換する前処理を行っており、
     * この機能を含めたテストを行うためのメソッドです
     *
     * @param array $data フォーム入力値
     * @return bool true:バリデート成功 false:バリデート失敗
     */
    private function validate(array $data): bool
    {
        $this->app->resolving(CategoryPutRequest::class, function ($resolved) use ($data) {
            $resolved->merge($data);
        });

        try {
            app(CategoryPutRequest::class);

            return true;
        } catch (ValidationException) {
            return false;
        }
    }

    /**
     * @test
     * @dataProvider categoryPutRequestDataProvider
     * @param array $values
     * @param bool $expect
     */
    public function 必須であることをバリデーションできていること(array $values, bool $expect)
    {
        $this->assertSame($expect, $this->validate($values));
    }

    /**
     * @return array
     */
    public function categoryPutRequestDataProvider(): array
    {
        return [
            '空文字列の場合' => [
                [
                    'categoryName' => [
                        '1' => ''
                    ]
                ],
                false
            ],
            'nullの場合' => [
                [
                    'categoryName' => [
                        '1' => null
                    ]
                ],
                false
            ],
            '半角スペースの場合' => [
                [
                    'categoryName' => [
                        '1' => ' '
                    ]
                ],
                false
            ],
            '全角スペースの場合' => [
                [
                    'categoryName' => [
                        '1' => '　'
                    ]
                ],
                false
            ],
            '有効な文字列の場合' => [
                [
                    'categoryName' => [
                        '1' => '光熱費'
                    ]
                ],
                true
            ],
        ];
    }
}
