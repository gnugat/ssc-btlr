<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Cht\Message\Logs\ListLogs\Matching;

use PHPUnit\Framework\Attributes\Test;
use Ssc\Btlr\App\Filesystem\Format\ReadYamlFile;
use Ssc\Btlr\Cht\Message\Logs\ListLogs\Matching\Slice;
use Ssc\Btlr\Cht\Message\Logs\Type;
use tests\Ssc\Btlr\AppTest\BtlrServiceTestCase;

class SliceTest extends BtlrServiceTestCase
{
    #[Test]
    public function it_can_match_all(): void
    {
        // Fixtures
        $offset = 0;
        $length = null;

        $filenames = [
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

        // Dummies
        $readYamlFile = $this->prophesize(ReadYamlFile::class);

        // Stubs & Mocks
        foreach ($filenames as $index => $filename) {
            $readYamlFile->in($filenames[$index])
                ->willReturn($logs[$index]);
        }

        // Assertion
        $slice = new Slice(
            $offset,
            $length,
        );
        self::assertSame($logs, $slice->against(
            $filenames,
            $readYamlFile->reveal(),
        ));
    }

    #[Test]
    public function it_can_match_the_begining(): void
    {
        // Fixtures
        $offset = 0;
        $length = 2;

        $filenames = [
            './var/cht/logs/messages/1968-04-02T18:40:23+00:00_000_user_prompt.yaml',
            './var/cht/logs/messages/1968-04-02T18:40:42+00:00_900_model_completion.yaml',
            './var/cht/logs/messages/1968-04-03T19:57:23+00:00_000_user_prompt.yaml',
            './var/cht/logs/messages/1968-04-03T19:57:42+00:00_900_model_completion.yaml',
            './var/cht/logs/messages/1968-04-04T06:13:37+00:00_000_user_prompt.yaml',
            './var/cht/logs/messages/1968-04-04T06:44:23+00:00_900_model_completion.yaml',
        ];
        $matchingLogToFilenameIndexes = [
            0 => 0,
            1 => 1,
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
        $slice = new Slice(
            $offset,
            $length,
        );
        self::assertSame($logs, $slice->against(
            $filenames,
            $readYamlFile->reveal(),
        ));
    }

    #[Test]
    public function it_can_match_the_middle(): void
    {
        // Fixtures
        $offset = 4;
        $length = 2;

        $filenames = [
            './var/cht/logs/messages/1968-04-02T18:40:23+00:00_000_user_prompt.yaml',
            './var/cht/logs/messages/1968-04-02T18:40:42+00:00_900_model_completion.yaml',
            './var/cht/logs/messages/1968-04-03T19:57:23+00:00_000_user_prompt.yaml',
            './var/cht/logs/messages/1968-04-03T19:57:42+00:00_900_model_completion.yaml',
            './var/cht/logs/messages/1968-04-04T06:13:37+00:00_000_user_prompt.yaml',
            './var/cht/logs/messages/1968-04-04T06:44:23+00:00_900_model_completion.yaml',
            './var/cht/logs/messages/1968-04-04T06:58:00+00:00_000_user_prompt.yaml',
            './var/cht/logs/messages/1968-04-04T07:00:00+00:00_900_model_completion.yaml',
        ];
        $matchingLogToFilenameIndexes = [
            0 => 4,
            1 => 5,
        ];

        $logs = [
            [
                'entry' => 'Do you read me?',
                'time' => '1968-04-04T06:13:37+00:00',
                'type' => Type::USER_PROMPT['name'],
            ],
            [
                'entry' => 'Affirmative dev, I read you',
                'time' => '1968-04-04T06:44:23+00:00',
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
        $slice = new Slice(
            $offset,
            $length,
        );
        self::assertSame($logs, $slice->against(
            $filenames,
            $readYamlFile->reveal(),
        ));
    }

    #[Test]
    public function it_can_match_the_end(): void
    {
        // Fixtures
        $offset = -2;
        $length = null;

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
                'time' => '1968-04-04T06:13:37+00:00',
                'type' => Type::USER_PROMPT['name'],
            ],
            [
                'entry' => 'Affirmative dev, I read you',
                'time' => '1968-04-04T06:44:23+00:00',
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
        $slice = new Slice(
            $offset,
            $length,
        );
        self::assertSame($logs, $slice->against(
            $filenames,
            $readYamlFile->reveal(),
        ));
    }
}
