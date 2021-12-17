<?php

namespace Tests\Unit\App\Services;

use App\Services\PaymentService;
use Carbon\Carbon;
use Tests\TestCase;

class PaymentServiceTest extends TestCase
{
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
}
