<?php

namespace Tests\Unit\App\Http\Requests;

use App\Http\Requests\CategoryRequest;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CategoryRequestTest extends TestCase
{
    /**
     * CategoryRequestではprepareForValidation()で全角スペースを半角スペースに変換する前処理を行っており、
     * この機能を含めたテストを行うためのメソッドです
     *
     * @param array $data フォーム入力値
     * @return bool true:バリデート成功 false:バリデート失敗
     */
    private function validate(array $data): bool
    {
        $this->app->resolving(CategoryRequest::class, function ($resolved) use ($data) {
            $resolved->merge($data);
        });

        try {
            app(CategoryRequest::class);

            return true;
        } catch (ValidationException) {
            return false;
        }
    }

    /**
     * @test
     * @dataProvider categoryRequestDataProvider
     */
    public function 必須であることをバリデーションできていること(?string $value, bool $expect)
    {
        $this->assertSame($expect, $this->validate(['categoryName' => $value]));
    }

    /**
     * @return array
     */
    public function categoryRequestDataProvider(): array
    {
        return [
            '空文字列の場合' => ['', false],
            'nullの場合' => [null, false],
            '半角スペースの場合' => [' ', false],
            '全角スペースの場合' => ['　', false],
            '文字列の場合' => ['光熱費', true],
        ];
    }
}
