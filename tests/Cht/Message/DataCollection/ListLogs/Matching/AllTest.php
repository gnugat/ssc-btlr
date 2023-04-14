<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Cht\Message\DataCollection\ListLogs\Matching;

use Ssc\Btlr\App\Filesystem\ReadFile;
use Ssc\Btlr\Cht\Message\DataCollection\ListLogs\Matching\All;
use Ssc\Btlr\Cht\Message\DataCollection\Type;
use tests\Ssc\Btlr\AppTest\BtlrServiceTestCase;

class AllTest extends BtlrServiceTestCase
{
    /**
     * @test
     */
    public function it_matches_against_all(): void
    {
        // Fixtures
        $filenames = [
            './var/cht/logs/last_messages/1968-04-02T18:40:23+00:00_000_user_prompt.json',
            './var/cht/logs/last_messages/1968-04-02T18:40:23+00:00_500_augmented_prompt.json',
            './var/cht/logs/last_messages/1968-04-02T18:40:42+00:00_900_model_completion.json',
        ];

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

        // Dummies
        $readFile = $this->prophesize(ReadFile::class);

        // Stubs & Mocks
        foreach ($filenames as $index => $filename) {
            $readFile->in($filename)
                ->willReturn(json_encode($logs[$index]));
        }

        // Assertion
        $all = new All();
        self::assertSame($logs, $all->against(
            $filenames,
            $readFile->reveal(),
        ));
    }
}
