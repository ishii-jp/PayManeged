<?php

namespace Tests\Unit\App\Events;

use Illuminate\Support\Facades\Event;
use App\Events\InputPaymentCompleted;
use Tests\TestCase;

class InputPaymentCompletedTest extends TestCase
{
    /**
     * イベントがディスパッチされることのテスト
     */
    public function testInputPaymentCompleted()
    {
        Event::fake(); // リスナを実際には実行しないようにします

        // イベントをディスパッチ
        InputPaymentCompleted::dispatch(
            'test-email',
            'test-name',
            'test-year',
            'test-month',
            ['test-payment1', 'test-payment2'],
            'test-paymentSum'
        );

        Event::assertDispatched(InputPaymentCompleted::class); // ディスパッチされたことを検証
    }
}
