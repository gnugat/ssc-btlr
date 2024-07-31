<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Cht\Message\Logs\Messages;

use PHPUnit\Framework\Attributes\Test;
use Ssc\Btlr\Cht\Message\Logs\Messages\FormatAsConversation;
use Ssc\Btlr\Cht\Message\Logs\Type;
use tests\Ssc\Btlr\AppTest\BtlrServiceTestCase;

class FormatAsConversationTest extends BtlrServiceTestCase
{
    #[Test]
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
                ." {$logs[2]['entry']}";

        // Assertion
        $formatAsConversation = new FormatAsConversation(
        );
        self::assertSame($conversation, $formatAsConversation->the(
            $logs,
        ));
    }
}
