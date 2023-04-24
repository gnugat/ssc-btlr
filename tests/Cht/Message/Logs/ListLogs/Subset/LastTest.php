<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Cht\Message\Logs\ListLogs\Subset;

use Ssc\Btlr\Cht\Message\Logs\ListLogs\Subset\Last;
use Ssc\Btlr\Cht\Message\Logs\Type;
use tests\Ssc\Btlr\AppTest\BtlrServiceTestCase;

class LastTest extends BtlrServiceTestCase
{
    /**
     * @test
     */
    public function it_can_return_last_x_logs(): void
    {
        // Fixtures
        $length = 2;

        $logs = [
            [
                'entry' => 'Write code for me, please',
                'time' => '1968-04-02T18:38:23+00:00',
                'type' => Type::USER_PROMPT['name'],
            ],
            [
                'entry' => '...',
                'time' => '1968-04-02T18:38:42+00:00',
                'type' => Type::MODEL_COMPLETION['name'],
            ],
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
            [
                'entry' => 'Write code for me, please',
                'time' => '1968-04-02T18:42:23+00:00',
                'type' => Type::USER_PROMPT['name'],
            ],
            [
                'entry' => "I'm afraid I can't do that, dev",
                'time' => '1968-04-02T18:42:42+00:00',
                'type' => Type::MODEL_COMPLETION['name'],
            ],
        ];
        $subsetLogs = [
            $logs[4],
            $logs[5],
        ];

        // Assertion
        $last = new Last(
            $length,
        );
        self::assertSame($subsetLogs, $last->of(
            $logs,
        ));
    }

    /**
     * @test
     */
    public function it_can_return_all(): void
    {
        // Fixtures
        $length = Last::ALL;

        $logs = [
            [
                'entry' => 'Write code for me, please',
                'time' => '1968-04-02T18:38:23+00:00',
                'type' => Type::USER_PROMPT['name'],
            ],
            [
                'entry' => '...',
                'time' => '1968-04-02T18:38:42+00:00',
                'type' => Type::MODEL_COMPLETION['name'],
            ],
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
            [
                'entry' => 'Write code for me, please',
                'time' => '1968-04-02T18:42:23+00:00',
                'type' => Type::USER_PROMPT['name'],
            ],
            [
                'entry' => "I'm afraid I can't do that, dev",
                'time' => '1968-04-02T18:42:42+00:00',
                'type' => Type::MODEL_COMPLETION['name'],
            ],
        ];

        // Assertion
        $last = new Last(
            $length,
        );
        self::assertSame($logs, $last->of(
            $logs,
        ));
    }

    /**
     * @test
     */
    public function it_can_return_none(): void
    {
        // Fixtures
        $length = Last::NONE;

        $logs = [
            [
                'entry' => 'Write code for me, please',
                'time' => '1968-04-02T18:38:23+00:00',
                'type' => Type::USER_PROMPT['name'],
            ],
            [
                'entry' => '...',
                'time' => '1968-04-02T18:38:42+00:00',
                'type' => Type::MODEL_COMPLETION['name'],
            ],
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
            [
                'entry' => 'Write code for me, please',
                'time' => '1968-04-02T18:42:23+00:00',
                'type' => Type::USER_PROMPT['name'],
            ],
            [
                'entry' => "I'm afraid I can't do that, dev",
                'time' => '1968-04-02T18:42:42+00:00',
                'type' => Type::MODEL_COMPLETION['name'],
            ],
        ];
        $subsetLogs = [];

        // Assertion
        $last = new Last(
            $length,
        );
        self::assertSame($subsetLogs, $last->of(
            $logs,
        ));
    }

    /**
     * @test
     */
    public function it_returns_all_if_x_is_invalid(): void
    {
        // Fixtures
        $length = -2;

        $logs = [
            [
                'entry' => 'Write code for me, please',
                'time' => '1968-04-02T18:38:23+00:00',
                'type' => Type::USER_PROMPT['name'],
            ],
            [
                'entry' => '...',
                'time' => '1968-04-02T18:38:42+00:00',
                'type' => Type::MODEL_COMPLETION['name'],
            ],
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
            [
                'entry' => 'Write code for me, please',
                'time' => '1968-04-02T18:42:23+00:00',
                'type' => Type::USER_PROMPT['name'],
            ],
            [
                'entry' => "I'm afraid I can't do that, dev",
                'time' => '1968-04-02T18:42:42+00:00',
                'type' => Type::MODEL_COMPLETION['name'],
            ],
        ];

        // Assertion
        $last = new Last(
            $length,
        );
        self::assertSame($logs, $last->of(
            $logs,
        ));
    }
}
