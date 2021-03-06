<?php

namespace Tests\Unit\App\Models;

use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        // DB接続先がテスト用に切り替わっているか確認
        // dd(env('APP_ENV'), env('DB_DATABASE'), env('DB_CONNECTION'));
    }

    /**
     * @test
     */
    public function register_レコードがなければcreateされること()
    {
        self::assertSame(0, count(Payment::all())); // register実行前のpaymentsテーブルの件数検証
        Payment::register('2021', '12', '1', ['1' => '50000']);
        self::assertSame(1, count(Payment::all())); // register実行後のpaymentsテーブルの件数検証
    }

    /**
     * @test
     */
    public function register_同じデータの登録をした場合重複して登録されないこと()
    {
        // 1回目の実行
        Payment::register('2021', '12', '1', ['1' => '50000']);
        self::assertSame(1, count(Payment::all()));

        // 2回目の実行
        Payment::register('2021', '12', '1', ['1' => '50000']);
        self::assertSame(1, count(Payment::all()));
    }

    /**
     * @test
     */
    public function register_同じデータの登録をした場合updated_atの値は上書きされないこと()
    {
        // 1回目の実行
        Payment::register('2021', '12', '1', ['1' => '50000']);
        $firstResult = Payment::first();
        $firstCreatedAt = (new Carbon($firstResult->created_at))->toDateTimeString();
        $firstUpdatedAt = (new Carbon($firstResult->updated_at))->toDateTimeString();

        // 2回目の実行
        Payment::register('2021', '12', '1', ['1' => '50000']);
        $secondResult = Payment::first();

        self::assertSame($firstCreatedAt, (new Carbon($secondResult->created_at))->toDateTimeString());
        self::assertSame($firstUpdatedAt, (new Carbon($secondResult->updated_at))->toDateTimeString());
    }

    /**
     * @test
     */
    public function register_別の年データの登録をした場合別のレコードとしてcreateされること()
    {
        // 1回目の実行
        Payment::register('2021', '12', '1', ['1' => '50000']);
        // 2回目の実行
        Payment::register('2020', '12', '1', ['1' => '50000']);

        self::assertSame(2, count(Payment::all()));
    }

    /**
     * @test
     */
    public function register_別の月データの登録をした場合別のレコードとしてcreateされること()
    {
        // 1回目の実行
        Payment::register('2021', '12', '1', ['1' => '50000']);
        // 2回目の実行
        Payment::register('2021', '11', '1', ['1' => '50000']);

        self::assertSame(2, count(Payment::all()));
    }

    /**
     * @test
     */
    public function register_別のユーザーIDデータの登録をした場合別のレコードとしてcreateされること()
    {
        // 1回目の実行
        Payment::register('2021', '12', '1', ['1' => '50000']);
        // 2回目の実行
        Payment::register('2021', '12', '2', ['1' => '50000']);

        self::assertSame(2, count(Payment::all()));
    }
}
