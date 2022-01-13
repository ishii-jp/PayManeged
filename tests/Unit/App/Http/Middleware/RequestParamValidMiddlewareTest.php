<?php

namespace Tests\Unit\App\Http\Middleware;

use App\Http\Middleware\RequestParamValidMiddleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;

class RequestParamValidMiddlewareTest extends TestCase
{
    private object $requestParamValid;

    protected function setUp(): void
    {
        parent::setUp();
        $this->requestParamValid = app(RequestParamValidMiddleware::class);
    }

    /**
     * @test
     */
    public function handle_指定のクエリパラメータがnullの場合はnextすること()
    {
        $request = new Request([], [], [], [], [], []);
        $this->requestParamValid->handle($request, function () {
            $this->assertTrue(true);
        });
    }

    /**
     * @test
     */
    public function handle_指定のクエリパラメータ以外が含まれている場合はnextすること()
    {
        $request = new Request(['month' => '12'], [], [], [], [], []);
        $this->requestParamValid->handle($request, function () {
            $this->assertTrue(true);
        });
    }

    /**
     * @test
     */
    public function handle_指定のクエリパラメータが存在しかつ正規表現にマッチする値の場合はnextすること()
    {
        $year = '2999';
        $request = new Request(['year' => $year], [], [], [], [], []);
        $this->requestParamValid->handle($request, function () {
            $this->assertTrue(true);
        });

        $this->assertSame($year, $request->query('year'));
    }

    /**
     * @test
     */
    public function handle_指定のクエリパラメータが存在しかつ正規表現にマッチしない値の場合は404として例外がスローされること()
    {
        $this->expectException(NotFoundHttpException::class);
        
        $year = '3999';
        $request = new Request(['year' => $year], [], [], [], [], []);
        $this->requestParamValid->handle($request, function () {
            $this->fail('ミドルウェアがnextし本クロージャが実行されたのでテストを失敗します');
        });
    }
}
