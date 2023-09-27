<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Cht\Message\Logs\ListLogs\Subset;

use Ssc\Btlr\Cht\Message\Logs\ListLogs\Subset\All;
use Ssc\Btlr\Cht\Message\Logs\Type;
use tests\Ssc\Btlr\AppTest\BtlrServiceTestCase;

class AllTest extends BtlrServiceTestCase
{
    /**
     * @test
     */
    public function it_returns_all(): void
    {
        // Fixtures
        $logs = [
            [
                'entry' => 'Do you read me?',
                'time' => '1968-04-02T18:40:23+00:00',
                'type' => Type::USER_PROMPT['name'],
            ],
            [
                'entry' => 'Affirmative dev, I read you',
                'time' => '1968-04-02T18:40:42+00:00',
                'type' => Type::MODEL_COMPLETION['name'],
            ],
        ];

        // Assertion
        $all = new All();
        self::assertSame($logs, $all->of(
            $logs,
        ));
    }
}
