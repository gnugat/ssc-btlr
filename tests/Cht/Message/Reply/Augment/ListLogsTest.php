<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Cht\Message\Reply\Augment;

use Ssc\Btlr\App\Filesystem\ListFiles;
use Ssc\Btlr\App\Filesystem\ReadFile;
use Ssc\Btlr\Cht\Message\Reply\Augment\ListLogs;
use Ssc\Btlr\Cht\Message\Reply\Log\Type;
use tests\Ssc\Btlr\AppTest\BtlrServiceTestCase;

class ListLogsTest extends BtlrServiceTestCase
{
    /**
     * @test
     */
    public function it_gets_logs(): void
    {
        // Fixtures
        $logsFilename = './var/cht/logs/last_messages';

        $logsFilenames = [
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
        $listFiles = $this->prophesize(ListFiles::class);
        $readFile = $this->prophesize(ReadFile::class);

        // Stubs & Mocks
        $listFiles->in($logsFilename)
            ->willReturn($logsFilenames);
        foreach ($logsFilenames as $index => $logFilename) {
            $readFile->in($logFilename)
                ->willReturn(json_encode($logs[$index]));
        }

        // Assertion
        $listLogs = new ListLogs(
            $listFiles->reveal(),
            $readFile->reveal(),
        );
        $actualLogs = $listLogs->in(
            $logsFilename,
        );
        self::assertSame($logs, $actualLogs);
    }
}
