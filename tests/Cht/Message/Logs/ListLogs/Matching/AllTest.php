<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Cht\Message\Logs\ListLogs\Matching;

use PHPUnit\Framework\Attributes\Test;
use Ssc\Btlr\App\Filesystem\Format\ReadYamlFile;
use Ssc\Btlr\Cht\Message\Logs\ListLogs\Matching\All;
use Ssc\Btlr\Cht\Message\Logs\Type;
use tests\Ssc\Btlr\AppTest\BtlrServiceTestCase;

class AllTest extends BtlrServiceTestCase
{
    #[Test]
    public function it_matches_against_all(): void
    {
        // Fixtures
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
            $readYamlFile->in($filename)
                ->willReturn($logs[$index]);
        }

        // Assertion
        $all = new All();
        self::assertSame($logs, $all->against(
            $filenames,
            $readYamlFile->reveal(),
        ));
    }
}
