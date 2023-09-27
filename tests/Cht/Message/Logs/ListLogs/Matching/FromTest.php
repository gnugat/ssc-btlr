<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Cht\Message\Logs\ListLogs\Matching;

use Ssc\Btlr\App\Filesystem\Format\ReadYamlFile;
use Ssc\Btlr\Cht\Message\Logs\ListLogs\Matching\From;
use Ssc\Btlr\Cht\Message\Logs\Type;
use tests\Ssc\Btlr\AppTest\BtlrServiceTestCase;

class FromTest extends BtlrServiceTestCase
{
    /**
     * @test
     */
    public function it_matches_all_starting_from_given_one(): void
    {
        // Fixtures
        $filename = './var/cht/logs/messages/1968-04-04T06:13:37+00:00_000_user_prompt.yaml';

        $filenames = [
            './var/cht/logs/messages/1968-04-02T18:40:23+00:00_000_user_prompt.yaml',
            './var/cht/logs/messages/1968-04-02T18:40:42+00:00_900_model_completion.yaml',
            './var/cht/logs/messages/1968-04-03T19:57:23+00:00_000_user_prompt.yaml',
            './var/cht/logs/messages/1968-04-03T19:57:42+00:00_900_model_completion.yaml',
            './var/cht/logs/messages/1968-04-04T06:13:37+00:00_000_user_prompt.yaml',
            './var/cht/logs/messages/1968-04-04T06:44:23+00:00_900_model_completion.yaml',
        ];
        $matchingLogToFilenameIndexes = [
            0 => 4,
            1 => 5,
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

        // Dummies
        $readYamlFile = $this->prophesize(ReadYamlFile::class);

        // Stubs & Mocks
        foreach ($matchingLogToFilenameIndexes as $logIndex => $filenameIndex) {
            $readYamlFile->in($filenames[$filenameIndex])
                ->willReturn($logs[$logIndex]);
        }

        // Assertion
        $from = new From(
            $filename,
        );
        self::assertSame($logs, $from->against(
            $filenames,
            $readYamlFile->reveal(),
        ));
    }
}
