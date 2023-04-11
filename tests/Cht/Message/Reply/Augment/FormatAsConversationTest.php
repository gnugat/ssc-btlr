<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Cht\Message\Reply\Augment;

use Ssc\Btlr\Cht\Message\DataCollection\Type;
use Ssc\Btlr\Cht\Message\Reply\Augment\FormatAsConversation;
use tests\Ssc\Btlr\AppTest\BtlrServiceTestCase;

class FormatAsConversationTest extends BtlrServiceTestCase
{
    /**
     * @test
     */
    public function it_formats_logs_as_conversation(): void
    {
        // Fixtures
        $logs = [
            [
                'entry' => 'Do you read me?',
                'time' => '1968-04-02T18:40:23+00:00',
                'type' => Type::USER_PROMPT['name'],
            ],
            [
                'entry' => "USER (1968-04-02T18:40:23+00:00): Do you read me?\nBLTR:",
                'time' => '1968-04-02T18:40:23+00:00',
                'type' => Type::AUGMENTED_PROMPT['name'],
            ],
            [
                'entry' => 'Affirmative dev, I read you',
                'time' => '1968-04-02T18:40:42+00:00',
                'type' => Type::MODEL_COMPLETION['name'],
            ],
        ];

        $conversation = "USER ({$logs[0]['time']}):"
                ." {$logs[0]['entry']}\n"
                ."BTLR ({$logs[2]['time']}):"
                ." {$logs[2]['entry']}\n";

        // Assertion
        $formatAsConversation = new FormatAsConversation(
        );
        $actualConversation = $formatAsConversation->the(
            $logs,
        );
        self::assertSame($conversation, $actualConversation);
    }
}
