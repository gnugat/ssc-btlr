<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Cht\Message\Logs;

use Ssc\Btlr\App\Filesystem\Format\ReadYamlFile;
use Ssc\Btlr\App\Filesystem\ListFiles;
use Ssc\Btlr\Cht\Message\Logs\ListLogs;
use Ssc\Btlr\Cht\Message\Logs\ListLogs\Matching;
use Ssc\Btlr\Cht\Message\Logs\ListLogs\Subset;
use Ssc\Btlr\Cht\Message\Logs\Type;
use tests\Ssc\Btlr\AppTest\BtlrServiceTestCase;

class ListLogsTest extends BtlrServiceTestCase
{
    /**
     * @test
     */
    public function it_lists_logs_that_match_against_given_criteria(): void
    {
        // Fixtures
        $logsFilename = './var/cht/logs/messages';

        $logsFilenames = [
            './var/cht/logs/messages/1968-04-02T18:40:23+00:00_000_user_prompt.yaml',
            './var/cht/logs/messages/1968-04-02T18:40:42+00:00_900_model_completion.yaml',
        ];
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
        $subsetLogs = [
            $logs[0],
        ];

        // Dummies
        $listFiles = $this->prophesize(ListFiles::class);
        $matching = $this->prophesize(Matching::class);
        $subset = $this->prophesize(Subset::class);
        $readYamlFile = $this->prophesize(ReadYamlFile::class);

        // Stubs & Mocks
        $listFiles->in($logsFilename)
            ->willReturn($logsFilenames);
        $matching->against($logsFilenames, $readYamlFile)
            ->willReturn($logs);
        $subset->of($logs)
            ->willReturn($subsetLogs);

        // Assertion
        $listLogs = new ListLogs(
            $listFiles->reveal(),
            $readYamlFile->reveal(),
        );
        self::assertSame($subsetLogs, $listLogs->in(
            $logsFilename,
            $matching->reveal(),
            $subset->reveal(),
        ));
    }
}
