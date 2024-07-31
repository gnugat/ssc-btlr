<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Cht\Message\Memory\Pointer;

use PHPUnit\Framework\Attributes\Test;
use Prophecy\Argument;
use Ssc\Btlr\App\Filesystem\Format\WriteYamlFile;
use Ssc\Btlr\Cht\Message\Logs\ListLogs;
use Ssc\Btlr\Cht\Message\Logs\ListLogs\Matching\Slice;
use Ssc\Btlr\Cht\Message\Logs\ListLogs\Subset\All;
use Ssc\Btlr\Cht\Message\Logs\MakeFilename;
use Ssc\Btlr\Cht\Message\Logs\Type;
use Ssc\Btlr\Cht\Message\Memory\Pointer\Make;
use tests\Ssc\Btlr\AppTest\BtlrServiceTestCase;

class MakeTest extends BtlrServiceTestCase
{
    #[Test]
    public function it_points_to_the_first_log(): void
    {
        // Fixtures
        $withConfig = [
            'chunk_memory_size' => 10,
            'last_messages_size' => 10,
            'llm_engine' => 'chatgpt-gpt-3.5-turbo',
            'logs_filename' => './var/cht/logs',
            'prompt_templates_filename' => './templates/cht/prompts',
        ];

        $lastMessagesFilename = "{$withConfig['logs_filename']}/messages";
        $memoryPointerFilename = "{$withConfig['logs_filename']}/memory_pointer.yaml";
        $logs = [
            [
                'entry' => 'Do you read me?',
                'time' => '1968-04-02T18:40:23+00:00',
                'type' => Type::USER_PROMPT['name'],
            ],
            [
                'entry' => 'USER (1968-04-02T18:40:23+00:00): Do you read me?'
                    ."\nBLTR:",
                'time' => '1968-04-02T18:40:23+00:00',
                'type' => Type::AUGMENTED_PROMPT['name'],
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
                'entry' => 'USER (1968-04-02T18:40:23+00:00): Do you read me?'
                    ."\nBLTR ('1968-04-02T18:40:42+00:00): Affirmative dev, I read you"
                    ."\nUSER ('1968-04-02T18:42:23+00:00): Write code for me, please"
                    ."\nBLTR:",
                'time' => '1968-04-02T18:42:23+00:00',
                'type' => Type::AUGMENTED_PROMPT['name'],
            ],
            [
                'entry' => "I'm afraid I can't do that, dev",
                'time' => '1968-04-02T18:42:42+00:00',
                'type' => Type::MODEL_COMPLETION['name'],
            ],
        ];
        $firstMakeFilename = './var/cht/logs/messages/1968-04-02T18:40:23+00:00_000_user_prompt.yaml';
        $brandNewMemoryPointer = [
            'current' => $firstMakeFilename,
            'previous' => $firstMakeFilename,
        ];

        // Dummies
        $listLogs = $this->prophesize(ListLogs::class);
        $makeFilename = $this->prophesize(MakeFilename::class);
        $slice = Argument::type(Slice::class);
        $all = Argument::type(All::class);
        $writeYamlFile = $this->prophesize(WriteYamlFile::class);

        // Stubs & Mocks
        $listLogs->in($lastMessagesFilename, matching: $slice, subset: $all)
            ->willReturn($logs);
        $makeFilename->for($logs[0], $withConfig)
            ->willReturn($firstMakeFilename);
        $writeYamlFile->in($memoryPointerFilename, $brandNewMemoryPointer)
            ->shouldBeCalled();

        // Assertion
        $make = new Make(
            $listLogs->reveal(),
            $makeFilename->reveal(),
            $writeYamlFile->reveal(),
        );
        self::assertSame($brandNewMemoryPointer, $make->brandNew(
            $withConfig,
        ));
    }
}
